<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Putus_total extends CI_Model
{
	/**
	 * Get decided case distribution by panel of judges
	 * 
	 * @param string $jenis_perkara Case type (Pdt.G, Pdt.P, or 'all')
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @param string $search Search query for judge name (optional)
	 * @return array Array of objects with case distribution data
	 */
	function putus_total($jenis_perkara, $lap_bulan, $lap_tahun, $search = null)
	{
		// Sanitize input parameters to prevent SQL injection
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build SQL query with efficient indexing columns
		$this->db->select('pp.majelis_hakim_id, pp.majelis_hakim_nama, pp.majelis_hakim_kode, COUNT(p.perkara_id) AS putus');
		$this->db->select('SUBSTRING_INDEX(pp.majelis_hakim_nama, "<br />", 1) as hakim_ketua', false);
		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->join('perkara_putusan ppu', 'p.perkara_id = ppu.perkara_id', 'inner');
		$this->db->where('YEAR(ppu.tanggal_putusan)', $lap_tahun);
		$this->db->where('MONTH(ppu.tanggal_putusan)', $lap_bulan);

		// Handle case type filter
		if ($jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara, 'both');
		}

		// Add search functionality
		if (!empty($search)) {
			$this->db->like('pp.majelis_hakim_nama', $search);
		}

		$this->db->group_by('pp.majelis_hakim_nama');
		$this->db->order_by('putus', 'DESC'); // Show highest caseload first

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Get detailed statistics about decided case distribution
	 * 
	 * @param string $jenis_perkara Case type
	 * @param string $lap_bulan Month
	 * @param string $lap_tahun Year
	 * @param string $search Search query (optional)
	 * @return object Statistics object
	 */
	function get_statistics($jenis_perkara, $lap_bulan, $lap_tahun, $search = null)
	{
		// Get main data
		$data = $this->putus_total($jenis_perkara, $lap_bulan, $lap_tahun, $search);

		// Initialize stats object
		$stats = new stdClass();

		if (!empty($data)) {
			// Calculate total cases
			$stats->total_perkara = array_sum(array_column($data, 'putus'));

			// Calculate total panels
			$stats->total_majelis = count($data);

			// Calculate average cases per panel
			$stats->avg_per_majelis = $stats->total_perkara / $stats->total_majelis;

			// Find panel with max load - Fix for empty array warning
			$putus_values = array_column($data, 'putus');
			if (!empty($putus_values)) {
				$stats->max_load = max($putus_values);

				// Find name of panel with max load
				foreach ($data as $row) {
					if ($row->putus == $stats->max_load) {
						$stats->max_load_majelis = $row->hakim_ketua;
						break;
					}
				}

				// Find panel with min load
				$stats->min_load = min($putus_values);

				// Find name of panel with min load
				foreach ($data as $row) {
					if ($row->putus == $stats->min_load) {
						$stats->min_load_majelis = $row->hakim_ketua;
						break;
					}
				}
			} else {
				// Default values when no values are found
				$stats->max_load = 0;
				$stats->max_load_majelis = 'N/A';
				$stats->min_load = 0;
				$stats->min_load_majelis = 'N/A';
			}

			// Count panels by load category
			$stats->high_load_count = 0;
			$stats->medium_load_count = 0;
			$stats->low_load_count = 0;

			foreach ($data as $row) {
				if ($row->putus > 10) {
					$stats->high_load_count++;
				} elseif ($row->putus > 5) {
					$stats->medium_load_count++;
				} else {
					$stats->low_load_count++;
				}
			}

			// Get additional statistics specific to decided cases
			$this->db->select('AVG(DATEDIFF(ppu.tanggal_putusan, p.tanggal_pendaftaran)) as avg_duration');
			$this->db->select('MIN(DATEDIFF(ppu.tanggal_putusan, p.tanggal_pendaftaran)) as min_duration');
			$this->db->select('MAX(DATEDIFF(ppu.tanggal_putusan, p.tanggal_pendaftaran)) as max_duration');
			$this->db->select('SUM(CASE WHEN ppu.tanggal_minutasi IS NOT NULL THEN 1 ELSE 0 END) as total_minutasi');
			$this->db->from('perkara p');
			$this->db->join('perkara_putusan ppu', 'p.perkara_id = ppu.perkara_id', 'inner');
			$this->db->where('YEAR(ppu.tanggal_putusan)', $lap_tahun);
			$this->db->where('MONTH(ppu.tanggal_putusan)', $lap_bulan);

			if ($jenis_perkara != 'all') {
				$this->db->like('p.nomor_perkara', $jenis_perkara, 'both');
			}

			$duration_query = $this->db->get();
			$duration_stats = $duration_query->row();

			$stats->avg_duration = round($duration_stats->avg_duration, 1);
			$stats->min_duration = $duration_stats->min_duration;
			$stats->max_duration = $duration_stats->max_duration;
			$stats->total_minutasi = $duration_stats->total_minutasi;
			$stats->minutasi_percentage = $stats->total_perkara > 0 ? round(($stats->total_minutasi / $stats->total_perkara) * 100, 2) : 0;
		} else {
			// Set default values if no data
			$stats->total_perkara = 0;
			$stats->total_majelis = 0;
			$stats->avg_per_majelis = 0;
			$stats->max_load = 0;
			$stats->max_load_majelis = 'N/A';
			$stats->min_load = 0;
			$stats->min_load_majelis = 'N/A';
			$stats->high_load_count = 0;
			$stats->medium_load_count = 0;
			$stats->low_load_count = 0;
			$stats->avg_duration = 0;
			$stats->min_duration = 0;
			$stats->max_duration = 0;
			$stats->total_minutasi = 0;
			$stats->minutasi_percentage = 0;
		}

		return $stats;
	}

	/**
	 * Get detailed decided cases for specific panel
	 * 
	 * @param string $majelis_id Panel ID
	 * @param string $jenis_perkara Case type (optional)
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year (optional)
	 * @return array Array of case objects
	 */
	function get_detail_cases($majelis_id, $jenis_perkara = null, $lap_bulan = null, $lap_tahun = null)
	{
		// Sanitize inputs
		$majelis_id = $this->db->escape_str($majelis_id);

		$this->db->select('p.perkara_id, p.nomor_perkara, p.jenis_perkara_nama, p.tanggal_pendaftaran');
		$this->db->select('pp.penetapan_majelis_hakim, pp.sidang_pertama');
		$this->db->select('ppu.tanggal_putusan, ppu.status_putusan_nama, ppu.tanggal_minutasi');
		$this->db->select('pp.majelis_hakim_nama');
		$this->db->select('DATEDIFF(ppu.tanggal_putusan, p.tanggal_pendaftaran) as durasi_perkara');
		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->join('perkara_putusan ppu', 'p.perkara_id = ppu.perkara_id', 'inner');

		// Filter by panel ID
		$this->db->where('pp.majelis_hakim_id', $majelis_id);

		// Optional filters
		if (!empty($jenis_perkara) && $jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara, 'both');
		}

		if (!empty($lap_bulan) && !empty($lap_tahun)) {
			$this->db->where('YEAR(ppu.tanggal_putusan)', $lap_tahun);
			$this->db->where('MONTH(ppu.tanggal_putusan)', $lap_bulan);
		} elseif (!empty($lap_tahun)) {
			$this->db->where('YEAR(ppu.tanggal_putusan)', $lap_tahun);
		}

		$this->db->order_by('ppu.tanggal_putusan', 'DESC');

		$query = $this->db->get();
		return $query->result();
	}
}