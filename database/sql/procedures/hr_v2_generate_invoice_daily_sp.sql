DROP PROCEDURE IF EXISTS hr_v2_generate_invoice_daily_sp;
DELIMITER $$
CREATE PROCEDURE hr_v2_generate_invoice_daily_sp(
    IN p_target_date DATE,
    IN p_dry_run BOOLEAN
)
BEGIN
    DECLARE v_done INT DEFAULT 0;
    DECLARE v_pelanggan_id BIGINT;
    DECLARE v_nama_lengkap VARCHAR(255);
    DECLARE v_unitid VARCHAR(50);
    DECLARE v_layanan_id_original BIGINT;
    DECLARE v_layanan_id_canonical BIGINT;
    DECLARE v_trx_order_id BIGINT;
    DECLARE v_exp_date DATE;
    DECLARE v_tgl_create DATE;
    DECLARE v_next_start DATE;
    DECLARE v_next_end DATE;
    DECLARE v_harga DOUBLE DEFAULT 0;
    DECLARE v_ppn_rate DECIMAL(5,2) DEFAULT 11.00;
    DECLARE v_ppn DOUBLE DEFAULT 0;
    DECLARE v_gtot DOUBLE DEFAULT 0;
    DECLARE v_has_existing INT DEFAULT 0;
    DECLARE v_new_id BIGINT DEFAULT 0;
    DECLARE v_new_no_tagihan VARCHAR(10);
    DECLARE v_seq INT DEFAULT 0;

    DECLARE v_total_candidate INT DEFAULT 0;
    DECLARE v_created INT DEFAULT 0;
    DECLARE v_skipped_existing INT DEFAULT 0;
    DECLARE v_skipped_invalid INT DEFAULT 0;
    DECLARE v_failed INT DEFAULT 0;

    DECLARE cur CURSOR FOR
        SELECT
            pelanggan_id,
            nama_lengkap,
            unitid,
            layanan_id_original,
            layanan_id_canonical,
            primary_trx_order_id,
            exp_date_final_candidate,
            DATE_ADD(exp_date_final_candidate, INTERVAL -11 DAY) AS tgl_create_invoice,
            DATE_ADD(exp_date_final_candidate, INTERVAL 1 DAY) AS next_invoice_period_start,
            DATE_ADD(DATE_ADD(exp_date_final_candidate, INTERVAL 1 MONTH), INTERVAL 0 DAY) AS next_invoice_period_end
        FROM tmp_hr_v2_invoice_candidates
        ORDER BY pelanggan_id;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_done = 1;

    DROP TEMPORARY TABLE IF EXISTS tmp_hr_v2_invoice_candidates;
    CREATE TEMPORARY TABLE tmp_hr_v2_invoice_candidates AS
    SELECT
        s.pelanggan_id,
        s.nama_lengkap,
        s.unitid,
        s.layanan_id_original,
        s.layanan_id_canonical,
        s.primary_trx_order_id,
        s.exp_date_final_candidate,
        CASE
            WHEN EXISTS (
                SELECT 1
                FROM trx_tagihan th
                JOIN trx_order o ON o.id = th.trx_order_id
                JOIN trx_tagihan_detail td ON td.trx_tagihan_id = th.id
                WHERE o.pelanggan_id = s.pelanggan_id
                  AND hr_v2_get_canonical_layanan_function(td.layanan_id) = s.layanan_id_canonical
                  AND IFNULL(th.status_tagihan,0) <> 4
                  AND hr_v2_parse_expdate_from_pemakaian_function(td.pemakaian) = DATE_ADD(DATE_ADD(s.exp_date_final_candidate, INTERVAL 1 MONTH), INTERVAL 0 DAY)
            ) THEN 1 ELSE 0
        END AS has_existing_invoice_same_expdate
    FROM hr_v2_expdate_source_view s
    WHERE s.exp_date_final_candidate IS NOT NULL
      AND DATE_ADD(s.exp_date_final_candidate, INTERVAL -11 DAY) = p_target_date;

    SELECT COUNT(*) INTO v_total_candidate FROM tmp_hr_v2_invoice_candidates;

    IF p_dry_run THEN
        SELECT
            'DRY_RUN' AS mode,
            p_target_date AS target_date,
            v_total_candidate AS total_candidate,
            SUM(has_existing_invoice_same_expdate = 1) AS skipped_existing,
            SUM(has_existing_invoice_same_expdate = 0 AND (unitid IS NULL OR primary_trx_order_id IS NULL)) AS skipped_invalid,
            SUM(has_existing_invoice_same_expdate = 0 AND unitid IS NOT NULL AND primary_trx_order_id IS NOT NULL) AS ready_to_create
        FROM tmp_hr_v2_invoice_candidates;

        SELECT
            pelanggan_id,
            nama_lengkap,
            unitid,
            layanan_id_original,
            layanan_id_canonical,
            primary_trx_order_id,
            exp_date_final_candidate,
            DATE_ADD(exp_date_final_candidate, INTERVAL 1 DAY) AS next_invoice_period_start,
            DATE_ADD(DATE_ADD(exp_date_final_candidate, INTERVAL 1 MONTH), INTERVAL 0 DAY) AS next_invoice_period_end,
            has_existing_invoice_same_expdate
        FROM tmp_hr_v2_invoice_candidates
        ORDER BY pelanggan_id;
    ELSE
        OPEN cur;
        read_loop: LOOP
            FETCH cur INTO
                v_pelanggan_id,
                v_nama_lengkap,
                v_unitid,
                v_layanan_id_original,
                v_layanan_id_canonical,
                v_trx_order_id,
                v_exp_date,
                v_tgl_create,
                v_next_start,
                v_next_end;

            IF v_done = 1 THEN
                LEAVE read_loop;
            END IF;

            IF v_unitid IS NULL OR v_trx_order_id IS NULL OR v_layanan_id_canonical IS NULL THEN
                SET v_skipped_invalid = v_skipped_invalid + 1;
            ELSE
                SELECT COUNT(*) INTO v_has_existing
                FROM trx_tagihan th
                JOIN trx_order o ON o.id = th.trx_order_id
                JOIN trx_tagihan_detail td ON td.trx_tagihan_id = th.id
                WHERE o.pelanggan_id = v_pelanggan_id
                  AND hr_v2_get_canonical_layanan_function(td.layanan_id) = v_layanan_id_canonical
                  AND IFNULL(th.status_tagihan,0) <> 4
                  AND hr_v2_parse_expdate_from_pemakaian_function(td.pemakaian) = v_next_end;

                IF v_has_existing > 0 THEN
                    SET v_skipped_existing = v_skipped_existing + 1;
                ELSE
                    SELECT harga INTO v_harga
                    FROM layanan
                    WHERE id = v_layanan_id_canonical AND aktif = '1'
                    LIMIT 1;

                    IF v_harga IS NULL OR v_harga <= 0 THEN
                        SET v_skipped_invalid = v_skipped_invalid + 1;
                    ELSE
                        SET v_ppn = ROUND(v_harga * v_ppn_rate / 100, 0);
                        SET v_gtot = v_harga + v_ppn;

                        SELECT IFNULL(MAX(CAST(RIGHT(no_tagihan, 4) AS UNSIGNED)), 0) + 1
                          INTO v_seq
                        FROM trx_tagihan
                        WHERE LEFT(no_tagihan, 6) = DATE_FORMAT(p_target_date, '%Y%m');

                        SET v_new_no_tagihan = CONCAT(DATE_FORMAT(p_target_date, '%Y%m'), LPAD(v_seq, 4, '0'));

                        INSERT INTO trx_tagihan (
                            tipe_tagihan,
                            no_tagihan,
                            trx_order_id,
                            tgl_tagihan,
                            periode_pemakaian,
                            tgl_jatuh_tempo,
                            sub_total,
                            ppn_tagihan,
                            gtot_tagihan,
                            status_tagihan,
                            created_at,
                            updated_at,
                            ket,
                            ppn_rate
                        ) VALUES (
                            '1',
                            v_new_no_tagihan,
                            v_trx_order_id,
                            p_target_date,
                            '-',
                            v_exp_date,
                            v_harga,
                            v_ppn,
                            v_gtot,
                            NULL,
                            NOW(),
                            NOW(),
                            'hr_v2_generate_invoice_daily_sp',
                            v_ppn_rate
                        );

                        SET v_new_id = LAST_INSERT_ID();

                        INSERT INTO trx_tagihan_detail (
                            trx_tagihan_id,
                            line_no,
                            layanan_id,
                            pemakaian,
                            amount_tagihan,
                            created_at,
                            updated_at
                        ) VALUES (
                            v_new_id,
                            1,
                            v_layanan_id_canonical,
                            CONCAT(DATE_FORMAT(v_next_start, '%d/%m/%Y'), ' - ', DATE_FORMAT(v_next_end, '%d/%m/%Y')),
                            v_harga,
                            NOW(),
                            NOW()
                        );

                        SET v_created = v_created + 1;
                    END IF;
                END IF;
            END IF;
        END LOOP;
        CLOSE cur;

        INSERT INTO Trx_logProses (tgl_proses, proses, keterangan)
        VALUES (
            NOW(),
            'Generate Invoice Harian v2',
            CONCAT(
                'target_date:', DATE_FORMAT(p_target_date, '%Y-%m-%d'),
                ' | total_candidate:', v_total_candidate,
                ' | created:', v_created,
                ' | skipped_existing:', v_skipped_existing,
                ' | skipped_invalid:', v_skipped_invalid,
                ' | failed:', v_failed
            )
        );

        SELECT
            'EXECUTE' AS mode,
            p_target_date AS target_date,
            v_total_candidate AS total_candidate,
            v_created AS created_count,
            v_skipped_existing AS skipped_existing,
            v_skipped_invalid AS skipped_invalid,
            v_failed AS failed_count;
    END IF;

    DROP TEMPORARY TABLE IF EXISTS tmp_hr_v2_invoice_candidates;
END$$
DELIMITER ;
