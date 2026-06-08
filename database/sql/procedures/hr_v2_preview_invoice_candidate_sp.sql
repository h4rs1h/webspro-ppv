DROP PROCEDURE IF EXISTS hr_v2_preview_invoice_candidate_sp;
DELIMITER $$
CREATE PROCEDURE hr_v2_preview_invoice_candidate_sp()
BEGIN
    SELECT
        s.pelanggan_id,
        s.nama_lengkap,
        s.unitid,
        s.layanan_id_original,
        s.layanan_id_canonical,
        s.current_exp_date,
        s.exp_date_final_candidate,
        DATE_ADD(s.exp_date_final_candidate, INTERVAL -11 DAY) AS tgl_create_invoice,
        DATE_ADD(s.exp_date_final_candidate, INTERVAL 1 DAY) AS next_invoice_period_start,
        DATE_ADD(DATE_ADD(s.exp_date_final_candidate, INTERVAL 1 MONTH), INTERVAL 0 DAY) AS next_invoice_period_end,
        s.source_used,
        s.pending_upgrade_order_id,
        s.pending_upgrade_target_exp_date,
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
      AND DATE_ADD(s.exp_date_final_candidate, INTERVAL -11 DAY) = CURDATE()
    ORDER BY s.exp_date_final_candidate, s.pelanggan_id, s.layanan_id_canonical, s.layanan_id_original;
END$$
DELIMITER ;
