<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_sisa_bulan_ini extends CI_Model
{
	/**
	 * Get remaining cases for the month by judge panel
	 * Optimized query with proper parameter binding
	 */
	function sisa_bulan_ini($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null, $search = null)
	{
		// Default to current month/year if not provided
		if (empty($lap_bulan)) $lap_bulan = date('m');
		if (empty($lap_tahun)) $lap_tahun = date('Y');
		if (empty($jenis_perkara)) $jenis_perkara = 'Pdt.G';

		// Calculate the last day of the month
		$last_day = date('t', strtotime("$lap_tahun-$lap_bulan-01"));
		$end_date = "$lap_tahun-$lap_bulan-$last_day";

		// Base query
		$this->db->select('pen.majelis_hakim_id, pen.majelis_hakim_nama, COUNT(p.perkara_id) AS sisa_bulan_ini');
		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'inner');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');

		// Filter conditions
		$this->db->where('p.tanggal_pendaftaran <=', $end_date);
		$this->db->where("(pp.tanggal_putusan IS NULL OR pp.tanggal_putusan > '$end_date')");

		// Case type filter
		if ($jenis_perkara !== 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		// Add search capability
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('pen.majelis_hakim_nama', $search);
			$this->db->or_like('p.nomor_perkara', $search);
			$this->db->group_end();
		}

		// Group and sort results
		$this->db->group_by('pen.majelis_hakim_nama');
		$this->db->order_by('sisa_bulan_ini', 'DESC');

		return $this->db->get()->result();
	}

	/**
	 * Get detailed cases for specific panel(s)
	 * 
	 * @param string $majelis_id Panel ID or comma-separated IDs
	 * @param string $jenis_perkara Case type filter
	 * @param string $lap_bulan Month filter
	 * @param string $lap_tahun Year filter
	 * @param string $search Search term
	 * @return array Case details
	 */
	function get_detail_cases($majelis_id, $jenis_perkara = null, $lap_bulan = null, $lap_tahun = null, $search = null)
	{
		// Default to current month/year if not provided
		if (empty($lap_bulan)) $lap_bulan = date('m');
		if (empty($lap_tahun)) $lap_tahun = date('Y');

		// Calculate the last day of the month
		$last_day = date('t', strtotime("$lap_tahun-$lap_bulan-01"));
		$end_date = "$lap_tahun-$lap_bulan-$last_day";

		// Build query for case details without jadwal_sidang table
		$this->db->select('p.perkara_id, p.nomor_perkara, p.jenis_perkara_nama, p.tanggal_pendaftaran');
		$this->db->select('DATEDIFF(NOW(), p.tanggal_pendaftaran) as usia_perkara');
		$this->db->select('pp.tanggal_putusan');
		// Removed the join with jadwal_sidang table
		$this->db->select('ph1.nama as nama_penggugat, ph2.nama as nama_tergugat');
		$this->db->select('pen.majelis_hakim_id, pen.majelis_hakim_nama');

		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'inner');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');

		// Join parties
		$this->db->join('perkara_pihak1 pp1', 'p.perkara_id = pp1.perkara_id AND pp1.urutan = 1', 'left');
		$this->db->join('pihak ph1', 'pp1.pihak_id = ph1.id', 'left');
		$this->db->join('perkara_pihak2 pp2', 'p.perkara_id = pp2.perkara_id AND pp2.urutan = 1', 'left');
		$this->db->join('pihak ph2', 'pp2.pihak_id = ph2.id', 'left');

		// Apply filters
		// Handle multiple majelis_ids separated by commas
		$this->db->where_in('pen.majelis_hakim_id', explode(',', $majelis_id));

		$this->db->where('p.tanggal_pendaftaran <=', $end_date);
		$this->db->where("(pp.tanggal_putusan IS NULL OR pp.tanggal_putusan > '$end_date')");

		// Case type filter
		if ($jenis_perkara !== 'all' && !empty($jenis_perkara)) {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		// Search filter
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like('p.nomor_perkara', $search);
			$this->db->or_like('ph1.nama', $search);
			$this->db->or_like('ph2.nama', $search);
			$this->db->group_end();
		}

		// Sort by case age (oldest first)
		$this->db->order_by('p.tanggal_pendaftaran', 'ASC');

		return $this->db->get()->result();
	}

	/**
	 * Get statistics for dashboard
	 */
	function get_statistics($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null)
	{
		// Default to current month/year if not provided
		if (empty($lap_bulan)) $lap_bulan = date('m');
		if (empty($lap_tahun)) $lap_tahun = date('Y');

		// Calculate the last day of the month
		$last_day = date('t', strtotime("$lap_tahun-$lap_bulan-01"));
		$end_date = "$lap_tahun-$lap_bulan-$last_day";

		// Query for basic statistics
		$this->db->select('COUNT(DISTINCT p.perkara_id) as total_cases');
		$this->db->select('COUNT(DISTINCT pen.majelis_hakim_id) as total_panels');
		$this->db->select('AVG(DATEDIFF(NOW(), p.tanggal_pendaftaran)) as avg_case_age');
		$this->db->select('MAX(DATEDIFF(NOW(), p.tanggal_pendaftaran)) as oldest_case');
		$this->db->select('MIN(DATEDIFF(NOW(), p.tanggal_pendaftaran)) as newest_case');

		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'inner');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');

		// Apply filters
		$this->db->where('p.tanggal_pendaftaran <=', $end_date);
		$this->db->where("(pp.tanggal_putusan IS NULL OR pp.tanggal_putusan > '$end_date')");

		// Case type filter
		if ($jenis_perkara !== 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		$stats = $this->db->get()->row();

		// Add case type distribution
		$stats->case_types = $this->get_case_type_distribution($jenis_perkara, $lap_bulan, $lap_tahun);

		// Add age distribution
		$stats->age_distribution = $this->get_case_age_distribution($jenis_perkara, $lap_bulan, $lap_tahun);

		return $stats;
	}

	/**
	 * Get case type distribution
	 */
	private function get_case_type_distribution($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null)
	{
		// Default to current month/year if not provided
		if (empty($lap_bulan)) $lap_bulan = date('m');
		if (empty($lap_tahun)) $lap_tahun = date('Y');

		// Calculate the last day of the month
		$last_day = date('t', strtotime("$lap_tahun-$lap_bulan-01"));
		$end_date = "$lap_tahun-$lap_bulan-$last_day";

		$this->db->select('p.jenis_perkara_nama, COUNT(p.perkara_id) as count');
		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'inner');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');

		// Apply filters
		$this->db->where('p.tanggal_pendaftaran <=', $end_date);
		$this->db->where("(pp.tanggal_putusan IS NULL OR pp.tanggal_putusan > '$end_date')");

		// Case type filter
		if ($jenis_perkara !== 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('count', 'DESC');

		return $this->db->get()->result();
	}

	/**
	 * Get case age distribution
	 */
	private function get_case_age_distribution($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null)
	{
		// Default to current month/year if not provided
		if (empty($lap_bulan)) $lap_bulan = date('m');
		if (empty($lap_tahun)) $lap_tahun = date('Y');

		// Calculate the last day of the month
		$last_day = date('t', strtotime("$lap_tahun-$lap_bulan-01"));
		$end_date = "$lap_tahun-$lap_bulan-$last_day";

		// Get counts by age ranges
		$this->db->select('
			SUM(CASE WHEN DATEDIFF(NOW(), p.tanggal_pendaftaran) <= 30 THEN 1 ELSE 0 END) as under_30_days,
			SUM(CASE WHEN DATEDIFF(NOW(), p.tanggal_pendaftaran) > 30 AND DATEDIFF(NOW(), p.tanggal_pendaftaran) <= 90 THEN 1 ELSE 0 END) as under_3_months,
			SUM(CASE WHEN DATEDIFF(NOW(), p.tanggal_pendaftaran) > 90 AND DATEDIFF(NOW(), p.tanggal_pendaftaran) <= 180 THEN 1 ELSE 0 END) as under_6_months,
			SUM(CASE WHEN DATEDIFF(NOW(), p.tanggal_pendaftaran) > 180 THEN 1 ELSE 0 END) as over_6_months
		');

		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'inner');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');

		// Apply filters
		$this->db->where('p.tanggal_pendaftaran <=', $end_date);
		$this->db->where("(pp.tanggal_putusan IS NULL OR pp.tanggal_putusan > '$end_date')");

		// Case type filter
		if ($jenis_perkara !== 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		return $this->db->get()->row();
	}
}
