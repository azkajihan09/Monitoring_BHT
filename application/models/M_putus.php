<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_putus extends CI_Model
{
	/**
	 * Get decided cases with optimized query
	 * 
	 * @param string $jenis_perkara Case type (optional)
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year (optional)
	 * @param string $search Search term (optional)
	 * @param int $limit Limit results (optional)
	 * @param int $offset Pagination offset (optional)
	 * @return array Array of case objects
	 */
	function get_putus($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null, $search = null, $limit = null, $offset = 0)
	{
		// Build base query with optimized joins and column selection
		$this->db->select('
            p.perkara_id, 
            p.nomor_perkara, 
            p.jenis_perkara_nama,
            p.tanggal_pendaftaran, 
            pp.tanggal_putusan, 
            pp.status_putusan_nama,
            pp.tanggal_minutasi,
            pp.amar_putusan,
            DATEDIFF(pp.tanggal_putusan, p.tanggal_pendaftaran) as durasi_perkara,
            pen.majelis_hakim_nama,
            pen.panitera_pengganti_text
        ');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');

		// Apply filters if provided
		if ($jenis_perkara && $jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		if ($lap_bulan && $lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
			$this->db->where('MONTH(pp.tanggal_putusan)', $lap_bulan);
		} else if ($lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
		}

		// Add search capability 
		if ($search) {
			$this->db->group_start();
			$this->db->like('p.nomor_perkara', $search);
			$this->db->or_like('p.jenis_perkara_nama', $search);
			$this->db->or_like('pen.majelis_hakim_nama', $search);
			$this->db->or_like('pp.status_putusan_nama', $search);
			$this->db->group_end();
		}

		// Add order by
		$this->db->order_by('pp.tanggal_putusan', 'DESC');

		// Apply pagination if limits provided
		if ($limit) {
			$this->db->limit($limit, $offset);
		}

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Count total records for pagination
	 * 
	 * @param string $jenis_perkara Case type (optional)
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year (optional)
	 * @param string $search Search term (optional)
	 * @return int Total records count
	 */
	function count_putus($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null, $search = null)
	{
		// Build count query with minimal columns for performance
		$this->db->select('COUNT(DISTINCT p.perkara_id) as total');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id');

		// Apply the same filters as get_putus
		if ($jenis_perkara && $jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		if ($lap_bulan && $lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
			$this->db->where('MONTH(pp.tanggal_putusan)', $lap_bulan);
		} else if ($lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
		}

		if ($search) {
			$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');
			$this->db->group_start();
			$this->db->like('p.nomor_perkara', $search);
			$this->db->or_like('p.jenis_perkara_nama', $search);
			$this->db->or_like('pen.majelis_hakim_nama', $search);
			$this->db->or_like('pp.status_putusan_nama', $search);
			$this->db->group_end();
		}

		$query = $this->db->get();
		$result = $query->row();
		return $result->total;
	}

	/**
	 * Get statistics for dashboard
	 * 
	 * @param string $jenis_perkara Case type (optional)
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year (optional)
	 * @return object Object with statistics
	 */
	function get_statistics($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null)
	{
		// Build query for statistics
		$this->db->select('
            COUNT(p.perkara_id) as total_perkara,
            AVG(DATEDIFF(pp.tanggal_putusan, p.tanggal_pendaftaran)) as avg_duration,
            MIN(DATEDIFF(pp.tanggal_putusan, p.tanggal_pendaftaran)) as min_duration,
            MAX(DATEDIFF(pp.tanggal_putusan, p.tanggal_pendaftaran)) as max_duration,
            SUM(CASE WHEN pp.tanggal_minutasi IS NOT NULL THEN 1 ELSE 0 END) as total_minutasi,
            ROUND(SUM(CASE WHEN pp.tanggal_minutasi IS NOT NULL THEN 1 ELSE 0 END) / COUNT(p.perkara_id) * 100, 2) as minutasi_percentage,
            COUNT(DISTINCT pen.majelis_hakim_id) as total_majelis
        ');

		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id');
		$this->db->join('perkara_penetapan pen', 'p.perkara_id = pen.perkara_id', 'left');

		// Apply filters
		if ($jenis_perkara && $jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		if ($lap_bulan && $lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
			$this->db->where('MONTH(pp.tanggal_putusan)', $lap_bulan);
		} else if ($lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
		}

		$query = $this->db->get();
		$stats = $query->row();

		// Get status distribution in a separate query 
		$this->db->select('pp.status_putusan_nama, COUNT(*) as total');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id');

		// Apply the same filters
		if ($jenis_perkara && $jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		if ($lap_bulan && $lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
			$this->db->where('MONTH(pp.tanggal_putusan)', $lap_bulan);
		} else if ($lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
		}

		$this->db->group_by('pp.status_putusan_nama');
		$this->db->order_by('total', 'DESC');

		$query = $this->db->get();
		$stats->status_distribution = $query->result();

		// Get case type distribution
		$this->db->select('p.jenis_perkara_nama, COUNT(*) as total');
		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id');

		// Apply the same filters
		if ($jenis_perkara && $jenis_perkara != 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara);
		}

		if ($lap_bulan && $lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
			$this->db->where('MONTH(pp.tanggal_putusan)', $lap_bulan);
		} else if ($lap_tahun) {
			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
		}

		$this->db->group_by('p.jenis_perkara_nama');
		$this->db->order_by('total', 'DESC');
		$this->db->limit(10); // Top 10 case types

		$query = $this->db->get();
		$stats->case_type_distribution = $query->result();

		// Get monthly distribution when full year is selected
		if ($lap_tahun && !$lap_bulan) {
			$this->db->select('MONTH(pp.tanggal_putusan) as month, COUNT(*) as total');
			$this->db->from('perkara p');
			$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id');

			if ($jenis_perkara && $jenis_perkara != 'all') {
				$this->db->like('p.nomor_perkara', $jenis_perkara);
			}

			$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);
			$this->db->group_by('MONTH(pp.tanggal_putusan)');
			$this->db->order_by('MONTH(pp.tanggal_putusan)');

			$query = $this->db->get();
			$stats->monthly_distribution = $query->result();
		}

		return $stats;
	}
}
