<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_cerai_kua extends CI_Model
{
	function cerai_kua($lap_bulan, $lap_tahun)
	{
		// Sanitasi input untuk mencegah SQL injection
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Query lebih efisien menggunakan JOIN yang selektif
		$sql = "SELECT 
                p.nomor_perkara,
                pac.tgl_akta_cerai,
                pac.nomor_akta_cerai,
                pp1.nama AS nama_p,
                ph1.alamat AS alamat_p,
                pp2.nama AS nama_t,
                ph2.alamat AS alamat_t,
                pdp.kua_tempat_nikah
            FROM 
                perkara p
                INNER JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
                LEFT JOIN perkara_pihak1 pp1 ON p.perkara_id = pp1.perkara_id
                LEFT JOIN perkara_pihak2 pp2 ON p.perkara_id = pp2.perkara_id
                LEFT JOIN pihak ph1 ON pp1.pihak_id = ph1.id
                LEFT JOIN pihak ph2 ON pp2.pihak_id = ph2.id
                LEFT JOIN perkara_data_pernikahan pdp ON p.perkara_id = pdp.perkara_id
            WHERE 
                YEAR(pac.tgl_akta_cerai) = ? 
                AND MONTH(pac.tgl_akta_cerai) = ?
            ORDER BY 
                pac.nomor_urut_akta_cerai";

		$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));
		return $query->result();
	}

	function get_statistics($lap_bulan, $lap_tahun)
	{
		// Sanitasi input
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Query untuk statistik dasar
		$sql = "SELECT 
                COUNT(DISTINCT pdp.kua_tempat_nikah) AS total_kua,
                COUNT(CASE WHEN pdp.kua_tempat_nikah IS NULL OR pdp.kua_tempat_nikah = '' THEN 1 END) AS blank_kua,
                AVG(CASE 
                    WHEN pdp.tgl_nikah IS NOT NULL THEN TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran)
                    ELSE NULL
                END) AS avg_usia_pernikahan,
                -- Statistik ketepatan waktu pelaporan (dalam 30 hari)
                ROUND(
                    (COUNT(CASE WHEN DATEDIFF(pac.tgl_akta_cerai, pp.tanggal_putusan) <= 30 THEN 1 END) / 
                    COUNT(*)) * 100, 
                1) AS on_time_percentage
            FROM 
                perkara p
                INNER JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
                LEFT JOIN perkara_data_pernikahan pdp ON p.perkara_id = pdp.perkara_id
                LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
            WHERE 
                YEAR(pac.tgl_akta_cerai) = ? 
                AND MONTH(pac.tgl_akta_cerai) = ?";

		$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));
		$result = $query->row();

		// Tambahkan distribusi KUA
		$result->kua_distribution = $this->get_kua_distribution($lap_bulan, $lap_tahun);

		return $result;
	}

	function get_kua_distribution($lap_bulan, $lap_tahun)
	{
		// Sanitasi input
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Query untuk mendapatkan jumlah kasus per KUA
		$sql = "SELECT 
                COALESCE(pdp.kua_tempat_nikah, 'Tidak Tercatat') as kua_tempat_nikah,
                COUNT(*) as total
            FROM 
                perkara p
                INNER JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
                LEFT JOIN perkara_data_pernikahan pdp ON p.perkara_id = pdp.perkara_id
            WHERE 
                YEAR(pac.tgl_akta_cerai) = ? 
                AND MONTH(pac.tgl_akta_cerai) = ?
            GROUP BY 
                kua_tempat_nikah
            ORDER BY 
                total DESC";

		$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));
		return $query->result_array();
	}
}
