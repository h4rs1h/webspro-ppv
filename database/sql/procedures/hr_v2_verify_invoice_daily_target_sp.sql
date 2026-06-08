DROP PROCEDURE IF EXISTS hr_v2_verify_invoice_daily_target_sp;
DELIMITER $$
CREATE PROCEDURE hr_v2_verify_invoice_daily_target_sp(
    IN p_target_date DATE
)
BEGIN
    SELECT
        COUNT(*) AS total_candidates,
        SUM(has_existing_invoice_same_expdate = 1) AS already_has_invoice,
        SUM(has_existing_invoice_same_expdate = 0) AS still_missing_invoice
    FROM (
        SELECT
            s.pelanggan_id,
            CASE
                WHEN EXISTS (
                    SELECT 1
                    FROM trx_tagihan th
                    JOIN trx_order o ON o.id = th.trx_order_id
                    JOIN trx_tagihan_detail td ON td.trx_tagihan_id = th.id
                    WHERE o.pelanggan_id = s.pelanggan_id
                      AND hr_v2_get_canonical_layanan_function(td.layanan_id) = s.layanan_id_canonical
                      AND IFNULL(th.status_tagihan, 0) <> 4
                      AND hr_v2_parse_expdate_from_pemakaian_function(td.pemakaian) =
                          DATE_ADD(DATE_ADD(s.exp_date_final_candidate, INTERVAL 1 MONTH), INTERVAL 0 DAY)
                ) THEN 1 ELSE 0
            END AS has_existing_invoice_same_expdate
        FROM hr_v2_expdate_source_view s
        WHERE s.exp_date_final_candidate IS NOT NULL
          AND DATE_ADD(s.exp_date_final_candidate, INTERVAL -11 DAY) = p_target_date
    ) q;
END$$
DELIMITER ;
