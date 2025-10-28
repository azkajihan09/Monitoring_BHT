<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Itsbat extends CI_Model
{
	/**
	 * Get Itsbat Nikah data by month and year
	 * 
	 * @param string $lap_bulan Month in two digits (01-12)
	 * @param string $lap_tahun Year in four digits (e.g., 2023)
	 * @return array Result object containing Itsbat Nikah data
	 */
	function itsbat($lap_bulan, $lap_tahun)
	{
		// Validate inputs
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build the query with proper joins and formatting
		$sql = "
            SELECT 
                p.nomor_perkara,
                p.tanggal_pendaftaran,
                pp.tanggal_putusan,
                sp.nama AS jenis_putusan,
                p1a.nama AS nama_p1,
                pi1.tanggal_lahir AS tanggal_lahir_p1,
                p1b.nama AS nama_p2,
                pi2.tanggal_lahir AS tanggal_lahir_p2,
                TIMESTAMPDIFF(YEAR, pi1.tanggal_lahir, p.tanggal_pendaftaran) AS usia_p1,
                TIMESTAMPDIFF(YEAR, pi2.tanggal_lahir, p.tanggal_pendaftaran) AS usia_p2,
                p.posita AS tahun_nikah
            FROM 
                perkara p
            LEFT JOIN 
                perkara_pihak1 p1a ON p.perkara_id = p1a.perkara_id AND p1a.urutan = 1
            LEFT JOIN 
                pihak pi1 ON p1a.pihak_id = pi1.id
            LEFT JOIN 
                perkara_pihak1 p1b ON p.perkara_id = p1b.perkara_id AND p1b.urutan = 2
            LEFT JOIN 
                pihak pi2 ON p1b.pihak_id = pi2.id
            LEFT JOIN 
                perkara_putusan pp ON p.perkara_id = pp.perkara_id
            LEFT JOIN 
                status_putusan sp ON pp.status_putusan_id = sp.id
            WHERE 
                YEAR(p.tanggal_pendaftaran) = ? 
                AND MONTH(p.tanggal_pendaftaran) = ?
                AND p.jenis_perkara_nama = 'Pengesahan Perkawinan/Istbat Nikah'
            ORDER BY 
                p.tanggal_pendaftaran ASC, p.nomor_perkara ASC";

		try {
			$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));

			// Check for any database errors
			if (!$query) {
				log_message('error', 'Database error: ' . $this->db->error()['message']);
				return array();
			}

			// Process the results to extract marriage date information
			$results = $query->result();

			return $results;
		} catch (Exception $e) {
			log_message('error', 'Error in itsbat query: ' . $e->getMessage());
			return array();
		}
	}

	/**
	 * Get summary statistics for Itsbat Nikah cases
	 * 
	 * @param string $lap_bulan Month in two digits (01-12)
	 * @param string $lap_tahun Year in four digits (e.g., 2023)
	 * @return array Result object containing statistics
	 */
	function getItsbatStatistics($lap_bulan, $lap_tahun)
	{
		// Validate inputs
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		$sql = "
            SELECT 
                COUNT(*) as total_perkara,
                AVG(TIMESTAMPDIFF(YEAR, pi1.tanggal_lahir, p.tanggal_pendaftaran)) AS avg_usia_p1,
                AVG(TIMESTAMPDIFF(YEAR, pi2.tanggal_lahir, p.tanggal_pendaftaran)) AS avg_usia_p2
            FROM 
                perkara p
            LEFT JOIN 
                perkara_pihak1 p1a ON p.perkara_id = p1a.perkara_id AND p1a.urutan = 1
            LEFT JOIN 
                pihak pi1 ON p1a.pihak_id = pi1.id
            LEFT JOIN 
                perkara_pihak1 p1b ON p.perkara_id = p1b.perkara_id AND p1b.urutan = 2
            LEFT JOIN 
                pihak pi2 ON p1b.pihak_id = pi2.id
            WHERE 
                YEAR(p.tanggal_pendaftaran) = ? 
                AND MONTH(p.tanggal_pendaftaran) = ?
                AND p.jenis_perkara_nama = 'Pengesahan Perkawinan/Istbat Nikah'";

		try {
			$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));
			return $query->row();
		} catch (Exception $e) {
			log_message('error', 'Error in itsbat statistics: ' . $e->getMessage());
			return null;
		}
	}
}
