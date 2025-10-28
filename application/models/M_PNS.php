<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_PNS extends CI_Model
{
	/**
	 * Get PNS cases with optimized query
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return array Array of PNS cases
	 */
	function pns($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters for security
		$jenis_perkara = $this->db->escape_str($jenis_perkara);
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build date condition for better performance
		$date_condition = "(
			(YEAR(p.tanggal_pendaftaran) = ? AND MONTH(p.tanggal_pendaftaran) = ?) OR
			(YEAR(pp.penetapan_majelis_hakim) = ? AND MONTH(pp.penetapan_majelis_hakim) = ?) OR
			(YEAR(pp.penetapan_hari_sidang) = ? AND MONTH(pp.penetapan_hari_sidang) = ?) OR
			(YEAR(pp.sidang_pertama) = ? AND MONTH(pp.sidang_pertama) = ?) OR
			(YEAR(put.tanggal_putusan) = ? AND MONTH(put.tanggal_putusan) = ?) OR
			put.tanggal_putusan IS NULL
		)";

		// Define the PNS job condition with LIKE expressions
		$pns_condition = "(ph.pekerjaan LIKE '%PNS%' OR ph.pekerjaan LIKE '%Pegawai Negeri Sipil%') AND ph.pekerjaan NOT LIKE '%Pensiunan%'";

		// Optimize query with proper JOIN clauses and aliasing
		$sql = "SELECT 
				p.nomor_perkara, 
				pp.majelis_hakim_nama, 
				pp.panitera_pengganti_text, 
				p.tanggal_pendaftaran, 
				p.jenis_perkara_nama,
				pp.penetapan_majelis_hakim, 
				pp.penetapan_hari_sidang, 
				pp.sidang_pertama, 
				put.tanggal_putusan, 
				put.status_putusan_nama,
				ph.pekerjaan,
				ph.nama as nama_pihak,
				ph.tempat_lahir,
				ph.tanggal_lahir,
				ph.alamat,
				ph.telepon,
				ph.pendidikan,
				ph.agama_nama,
				DATEDIFF(IFNULL(put.tanggal_putusan, CURDATE()), p.tanggal_pendaftaran) as durasi_perkara
			FROM 
				perkara p
				LEFT JOIN perkara_penetapan pp ON p.perkara_id = pp.perkara_id
				LEFT JOIN perkara_putusan put ON p.perkara_id = put.perkara_id
				LEFT JOIN perkara_pihak1 pp1 ON p.perkara_id = pp1.perkara_id AND pp1.urutan = 1
				LEFT JOIN pihak ph ON pp1.pihak_id = ph.id
			WHERE 
				$date_condition
				AND p.nomor_perkara LIKE ?
				AND $pns_condition
			ORDER BY 
				p.tanggal_pendaftaran DESC";

		$params = array(
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			'%' . $jenis_perkara . '%'
		);

		$query = $this->db->query($sql, $params);
		return $query->result();
	}

	/**
	 * Get statistics for PNS cases
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return object Statistics for PNS cases
	 */
	function get_statistics($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters
		$jenis_perkara = $this->db->escape_str($jenis_perkara);
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build date condition
		$date_condition = "(
			(YEAR(p.tanggal_pendaftaran) = ? AND MONTH(p.tanggal_pendaftaran) = ?) OR
			(YEAR(pp.penetapan_majelis_hakim) = ? AND MONTH(pp.penetapan_majelis_hakim) = ?) OR
			(YEAR(pp.penetapan_hari_sidang) = ? AND MONTH(pp.penetapan_hari_sidang) = ?) OR
			(YEAR(pp.sidang_pertama) = ? AND MONTH(pp.sidang_pertama) = ?) OR
			(YEAR(put.tanggal_putusan) = ? AND MONTH(put.tanggal_putusan) = ?) OR
			put.tanggal_putusan IS NULL
		)";

		// Define the PNS job condition
		$pns_condition = "(ph.pekerjaan LIKE '%PNS%' OR ph.pekerjaan LIKE '%Pegawai Negeri Sipil%') AND ph.pekerjaan NOT LIKE '%Pensiunan%'";

		// Query for statistics
		$sql = "SELECT 
				COUNT(*) as total_cases,
				SUM(CASE WHEN put.tanggal_putusan IS NOT NULL THEN 1 ELSE 0 END) as completed_cases,
				SUM(CASE WHEN put.tanggal_putusan IS NULL THEN 1 ELSE 0 END) as ongoing_cases,
				AVG(DATEDIFF(IFNULL(put.tanggal_putusan, CURDATE()), p.tanggal_pendaftaran)) as avg_duration,
				MAX(DATEDIFF(IFNULL(put.tanggal_putusan, CURDATE()), p.tanggal_pendaftaran)) as max_duration,
				MIN(CASE WHEN put.tanggal_putusan IS NOT NULL THEN DATEDIFF(put.tanggal_putusan, p.tanggal_pendaftaran) ELSE NULL END) as min_duration,
				COUNT(DISTINCT pp.majelis_hakim_kode) as total_judges,
				COUNT(DISTINCT pp.panitera_pengganti_id) as total_clerks
			FROM 
				perkara p
				LEFT JOIN perkara_penetapan pp ON p.perkara_id = pp.perkara_id
				LEFT JOIN perkara_putusan put ON p.perkara_id = put.perkara_id
				LEFT JOIN perkara_pihak1 pp1 ON p.perkara_id = pp1.perkara_id AND pp1.urutan = 1
				LEFT JOIN pihak ph ON pp1.pihak_id = ph.id
			WHERE 
				$date_condition
				AND p.nomor_perkara LIKE ?
				AND $pns_condition";

		$params = array(
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			$lap_tahun,
			$lap_bulan,
			'%' . $jenis_perkara . '%'
		);

		$query = $this->db->query($sql, $params);
		return $query->row();
	}

	/**
	 * Export PNS cases to Excel
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return array Array of PNS cases
	 */
	function export_data($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Get the same data as the pns function but with additional fields for export
		return $this->pns($jenis_perkara, $lap_bulan, $lap_tahun);
	}
}
