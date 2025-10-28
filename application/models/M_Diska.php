<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Diska extends CI_Model
{
	/**
	 * Get Dispensasi Kawin data by month and year or year only
	 * 
	 * @param string|null $lap_bulan Month in two digits (01-12) or null for all months
	 * @param string $lap_tahun Year in four digits (e.g., 2023)
	 * @return array Result object containing Dispensasi Kawin data
	 */
	function diska($lap_bulan, $lap_tahun)
	{
		// Validate inputs to prevent SQL injection
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Create date filter condition based on whether month is provided
		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_filter = "YEAR(p.tanggal_pendaftaran) = '$lap_tahun' AND MONTH(p.tanggal_pendaftaran) = '$lap_bulan'";
		} else {
			// If lap_bulan is null, we're filtering by year only
			$date_filter = "YEAR(p.tanggal_pendaftaran) = '$lap_tahun'";
		}

		// Build the comprehensive query with all necessary data
		$sql = "SELECT 
			p.perkara_id,
			p.nomor_perkara,
			p.tanggal_pendaftaran,
			p.jenis_perkara_nama,
			p.pihak1_text AS pemohon,
			
			-- Mempelai Laki-laki
			mpl.nama AS nama_laki,
			mpl.nik AS nik_laki,
			mpl.tempat_lahir AS tempat_lahir_laki,
			mpl.tanggal_lahir AS tanggal_lahir_laki,
			TIMESTAMPDIFF(YEAR, mpl.tanggal_lahir, p.tanggal_pendaftaran) AS umur_laki,
			
			-- Mempelai Perempuan
			mpw.nama AS nama_perempuan,
			mpw.nik AS nik_perempuan,
			mpw.tempat_lahir AS tempat_lahir_perempuan,
			mpw.tanggal_lahir AS tanggal_lahir_perempuan,
			TIMESTAMPDIFF(YEAR, mpw.tanggal_lahir, p.tanggal_pendaftaran) AS umur_perempuan,
			
			-- Data Pernikahan jika ada
			pdp.tgl_nikah,
			pdp.no_kutipan_akta_nikah,
			pdp.kua_tempat_nikah,
			
			-- Alasan Nikah
			pan.nama AS alasan_nikah,
			
			-- Status putusan
			pp.status_putusan_nama AS jenis_putusan,
			pp.tanggal_putusan,
			pp.amar_putusan,
			
			-- Untuk tracking sidang dan status
			pjs.tanggal_sidang,
			p.tahapan_terakhir_text
			
		FROM perkara p
		LEFT JOIN perkara_mempelai_dk mpl ON p.perkara_id = mpl.perkara_id AND mpl.jenis_mempelai = '1' -- Laki-laki
		LEFT JOIN perkara_mempelai_dk mpw ON p.perkara_id = mpw.perkara_id AND mpw.jenis_mempelai = '2' -- Perempuan
		LEFT JOIN perkara_alasan_nikah pan ON p.perkara_id = pan.perkara_id
		LEFT JOIN perkara_data_pernikahan pdp ON p.perkara_id = pdp.perkara_id
		LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
		LEFT JOIN (
			SELECT perkara_id, MIN(tanggal_sidang) as tanggal_sidang 
			FROM perkara_jadwal_sidang 
			GROUP BY perkara_id
		) pjs ON p.perkara_id = pjs.perkara_id
		
		WHERE p.jenis_perkara_nama LIKE '%Dispensasi Kawin%'
		AND $date_filter
		ORDER BY p.tanggal_pendaftaran DESC";

		try {
			$query = $this->db->query($sql);

			// Check for database errors
			if (!$query) {
				log_message('error', 'Database error in Diska model: ' . $this->db->error()['message']);
				return array();
			}

			return $query->result();
		} catch (Exception $e) {
			log_message('error', 'Error in diska query: ' . $e->getMessage());
			return array();
		}
	}

	/**
	 * Get statistics for Dispensasi Kawin cases
	 * 
	 * @param string|null $lap_bulan Month in two digits (01-12) or null for all months
	 * @param string $lap_tahun Year in four digits (e.g., 2023)
	 * @return object Statistics data
	 */
	function getStatistics($lap_bulan, $lap_tahun)
	{
		// Validate inputs
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Create date filter condition based on whether month is provided
		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_filter = "YEAR(p.tanggal_pendaftaran) = '$lap_tahun' AND MONTH(p.tanggal_pendaftaran) = '$lap_bulan'";
		} else {
			// If lap_bulan is null, we're filtering by year only
			$date_filter = "YEAR(p.tanggal_pendaftaran) = '$lap_tahun'";
		}

		$sql = "SELECT 
			COUNT(p.perkara_id) as total_perkara,
			AVG(TIMESTAMPDIFF(YEAR, mpl.tanggal_lahir, p.tanggal_pendaftaran)) AS avg_umur_laki,
			MIN(TIMESTAMPDIFF(YEAR, mpl.tanggal_lahir, p.tanggal_pendaftaran)) AS min_umur_laki,
			MAX(TIMESTAMPDIFF(YEAR, mpl.tanggal_lahir, p.tanggal_pendaftaran)) AS max_umur_laki,
			AVG(TIMESTAMPDIFF(YEAR, mpw.tanggal_lahir, p.tanggal_pendaftaran)) AS avg_umur_perempuan,
			MIN(TIMESTAMPDIFF(YEAR, mpw.tanggal_lahir, p.tanggal_pendaftaran)) AS min_umur_perempuan,
			MAX(TIMESTAMPDIFF(YEAR, mpw.tanggal_lahir, p.tanggal_pendaftaran)) AS max_umur_perempuan,
			SUM(CASE WHEN pp.status_putusan_nama IS NOT NULL THEN 1 ELSE 0 END) as total_putus,
			SUM(CASE WHEN pp.status_putusan_nama = 'DIKABULKAN' THEN 1 ELSE 0 END) as total_kabul,
			SUM(CASE WHEN pp.status_putusan_nama = 'DITOLAK' THEN 1 ELSE 0 END) as total_tolak
		FROM perkara p
		LEFT JOIN perkara_mempelai_dk mpl ON p.perkara_id = mpl.perkara_id AND mpl.jenis_mempelai = '1'
		LEFT JOIN perkara_mempelai_dk mpw ON p.perkara_id = mpw.perkara_id AND mpw.jenis_mempelai = '2'
		LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
		WHERE p.jenis_perkara_nama LIKE '%Dispensasi Kawin%'
		AND $date_filter";

		try {
			$query = $this->db->query($sql);
			return $query->row();
		} catch (Exception $e) {
			log_message('error', 'Error in diska statistics: ' . $e->getMessage());
			return null;
		}
	}
}
