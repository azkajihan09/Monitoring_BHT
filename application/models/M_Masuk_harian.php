<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Masuk_harian extends CI_Model
{
	/**
	 * Get case distribution by panel of judges per weekday
	 * 
	 * @param string $jenis_perkara Case type (Pdt.G, Pdt.P, or 'all')
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @param string $search Search query for judge name (optional)
	 * @return array Array of objects with daily case distribution data
	 */
	function masuk_harian($jenis_perkara, $lap_bulan, $lap_tahun, $search = null)
	{
		// Sanitize input parameters
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build SQL query with daily breakdown
		$this->db->select('pp.majelis_hakim_id, pp.majelis_hakim_nama, pp.majelis_hakim_kode');
		$this->db->select('SUBSTRING_INDEX(pp.majelis_hakim_nama, "<br />", 1) as hakim_ketua', false);
		
		// Count cases by weekday (1=Monday, 2=Tuesday, ..., 5=Friday)
		$this->db->select('SUM(CASE WHEN WEEKDAY(p.tanggal_pendaftaran) = 0 THEN 1 ELSE 0 END) as senin', false);
		$this->db->select('SUM(CASE WHEN WEEKDAY(p.tanggal_pendaftaran) = 1 THEN 1 ELSE 0 END) as selasa', false);
		$this->db->select('SUM(CASE WHEN WEEKDAY(p.tanggal_pendaftaran) = 2 THEN 1 ELSE 0 END) as rabu', false);
		$this->db->select('SUM(CASE WHEN WEEKDAY(p.tanggal_pendaftaran) = 3 THEN 1 ELSE 0 END) as kamis', false);
		$this->db->select('SUM(CASE WHEN WEEKDAY(p.tanggal_pendaftaran) = 4 THEN 1 ELSE 0 END) as jumat', false);
		$this->db->select('COUNT(p.perkara_id) AS total_masuk');
		
		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->where('YEAR(p.tanggal_pendaftaran)', $lap_tahun);
		$this->db->where('MONTH(p.tanggal_pendaftaran)', $lap_bulan);
		
		// Only include weekdays (Monday to Friday)
		$this->db->where('WEEKDAY(p.tanggal_pendaftaran) BETWEEN', '0 AND 4', false);

		// Handle case type filter
		if ($jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara, 'both');
		}

		// Add search functionality
		if (!empty($search)) {
			$this->db->like('pp.majelis_hakim_nama', $search);
		}

		$this->db->group_by('pp.majelis_hakim_nama');
		$this->db->order_by('total_masuk', 'DESC');

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Get daily case distribution summary
	 * 
	 * @param string $jenis_perkara Case type
	 * @param string $lap_bulan Month
	 * @param string $lap_tahun Year
	 * @param string $search Search query (optional)
	 * @return array Array with daily totals
	 */
	function masuk_per_hari($jenis_perkara, $lap_bulan, $lap_tahun, $search = null)
	{
		// Sanitize input parameters
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		$this->db->select('WEEKDAY(p.tanggal_pendaftaran) as hari_index');
		$this->db->select('COUNT(p.perkara_id) as jumlah_perkara');
		$this->db->select('DATE_FORMAT(p.tanggal_pendaftaran, "%W") as nama_hari', false);
		
		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->where('YEAR(p.tanggal_pendaftaran)', $lap_tahun);
		$this->db->where('MONTH(p.tanggal_pendaftaran)', $lap_bulan);
		
		// Only include weekdays
		$this->db->where('WEEKDAY(p.tanggal_pendaftaran) BETWEEN', '0 AND 4', false);

		// Handle case type filter
		if ($jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara, 'both');
		}

		// Add search functionality
		if (!empty($search)) {
			$this->db->like('pp.majelis_hakim_nama', $search);
		}

		$this->db->group_by('WEEKDAY(p.tanggal_pendaftaran)');
		$this->db->order_by('hari_index', 'ASC');

		$query = $this->db->get();
		$result = $query->result();
		
		// Convert to associative array with Indonesian day names
		$daily_data = [
			'senin' => 0,
			'selasa' => 0,
			'rabu' => 0,
			'kamis' => 0,
			'jumat' => 0
		];
		
		$day_mapping = [
			0 => 'senin',
			1 => 'selasa',
			2 => 'rabu',
			3 => 'kamis',
			4 => 'jumat'
		];
		
		foreach ($result as $row) {
			if (isset($day_mapping[$row->hari_index])) {
				$daily_data[$day_mapping[$row->hari_index]] = $row->jumlah_perkara;
			}
		}
		
		return $daily_data;
	}

	/**
	 * Get detailed statistics about daily case distribution
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
		$data = $this->masuk_harian($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$daily_data = $this->masuk_per_hari($jenis_perkara, $lap_bulan, $lap_tahun, $search);

		// Initialize stats object
		$stats = new stdClass();

		if (!empty($data)) {
			// Calculate total cases
			$stats->total_perkara = array_sum(array_column($data, 'total_masuk'));

			// Calculate total panels
			$stats->total_majelis = count($data);

			// Calculate average cases per panel
			$stats->avg_per_majelis = $stats->total_perkara / $stats->total_majelis;

			// Daily statistics
			$stats->total_senin = $daily_data['senin'];
			$stats->total_selasa = $daily_data['selasa'];
			$stats->total_rabu = $daily_data['rabu'];
			$stats->total_kamis = $daily_data['kamis'];
			$stats->total_jumat = $daily_data['jumat'];
			
			// Find busiest day
			$daily_totals = [
				'Senin' => $daily_data['senin'],
				'Selasa' => $daily_data['selasa'],
				'Rabu' => $daily_data['rabu'],
				'Kamis' => $daily_data['kamis'],
				'Jumat' => $daily_data['jumat']
			];
			
			$stats->busiest_day = array_keys($daily_totals, max($daily_totals))[0];
			$stats->busiest_day_count = max($daily_totals);
			
			$stats->quietest_day = array_keys($daily_totals, min($daily_totals))[0];
			$stats->quietest_day_count = min($daily_totals);

			// Find panel with max load
			$total_masuk_values = array_column($data, 'total_masuk');
			if (!empty($total_masuk_values)) {
				$stats->max_load = max($total_masuk_values);

				// Find name of panel with max load
				foreach ($data as $row) {
					if ($row->total_masuk == $stats->max_load) {
						$stats->max_load_majelis = $row->hakim_ketua;
						break;
					}
				}

				// Find panel with min load
				$stats->min_load = min($total_masuk_values);

				// Find name of panel with min load
				foreach ($data as $row) {
					if ($row->total_masuk == $stats->min_load) {
						$stats->min_load_majelis = $row->hakim_ketua;
						break;
					}
				}
			} else {
				$stats->max_load = 0;
				$stats->max_load_majelis = 'N/A';
				$stats->min_load = 0;
				$stats->min_load_majelis = 'N/A';
			}

			// Average daily distribution
			$stats->avg_daily = $stats->total_perkara / 5; // 5 working days
			
		} else {
			// Set default values if no data
			$stats->total_perkara = 0;
			$stats->total_majelis = 0;
			$stats->avg_per_majelis = 0;
			$stats->total_senin = 0;
			$stats->total_selasa = 0;
			$stats->total_rabu = 0;
			$stats->total_kamis = 0;
			$stats->total_jumat = 0;
			$stats->busiest_day = 'N/A';
			$stats->busiest_day_count = 0;
			$stats->quietest_day = 'N/A';
			$stats->quietest_day_count = 0;
			$stats->max_load = 0;
			$stats->max_load_majelis = 'N/A';
			$stats->min_load = 0;
			$stats->min_load_majelis = 'N/A';
			$stats->avg_daily = 0;
		}

		return $stats;
	}

	/**
	 * Get detailed cases for specific panel and day
	 * 
	 * @param string $majelis_id Panel ID
	 * @param string $hari Day of week (1=Monday, 5=Friday, null=all days)
	 * @param string $jenis_perkara Case type (optional)
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year (optional)
	 * @return array Array of case objects
	 */
	function get_detail_cases($majelis_id, $hari = null, $jenis_perkara = null, $lap_bulan = null, $lap_tahun = null)
	{
		// Sanitize inputs
		$majelis_id = $this->db->escape_str($majelis_id);

		$this->db->select('p.perkara_id, p.nomor_perkara, p.jenis_perkara_nama, p.tanggal_pendaftaran');
		$this->db->select('pp.penetapan_majelis_hakim, pp.sidang_pertama');
		$this->db->select('ppu.tanggal_putusan');
		$this->db->select('pp.majelis_hakim_nama');
		$this->db->select('WEEKDAY(p.tanggal_pendaftaran) as hari_index');
		$this->db->select('DATE_FORMAT(p.tanggal_pendaftaran, "%W") as nama_hari_en', false);
		$this->db->select('CASE WEEKDAY(p.tanggal_pendaftaran) WHEN 0 THEN "Senin" WHEN 1 THEN "Selasa" WHEN 2 THEN "Rabu" WHEN 3 THEN "Kamis" WHEN 4 THEN "Jumat" ELSE "Weekend" END as nama_hari', false);
		
		$this->db->from('perkara p');
		$this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->join('perkara_putusan ppu', 'p.perkara_id = ppu.perkara_id', 'left');

		// Filter by panel ID
		$this->db->where('pp.majelis_hakim_id', $majelis_id);
		
		// Only include weekdays
		$this->db->where('WEEKDAY(p.tanggal_pendaftaran) BETWEEN', '0 AND 4', false);

		// Filter by specific day if provided
		if (!empty($hari) && $hari >= 1 && $hari <= 5) {
			$this->db->where('WEEKDAY(p.tanggal_pendaftaran)', $hari - 1); // Convert to 0-based index
		}

		// Optional filters
		if (!empty($jenis_perkara) && $jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara, 'both');
		}

		if (!empty($lap_bulan) && !empty($lap_tahun)) {
			$this->db->where('YEAR(p.tanggal_pendaftaran)', $lap_tahun);
			$this->db->where('MONTH(p.tanggal_pendaftaran)', $lap_bulan);
		} elseif (!empty($lap_tahun)) {
			$this->db->where('YEAR(p.tanggal_pendaftaran)', $lap_tahun);
		}

		$this->db->order_by('p.tanggal_pendaftaran', 'ASC');

		$query = $this->db->get();
		return $query->result();
	}
}