<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_odp extends CI_Model
{
	/**
	 * Get ODP cases based on filters with improved query performance
	 * 
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year
	 * @return array List of ODP cases
	 */
	function odp($lap_bulan = null, $lap_tahun)
	{
		// Use query binding to prevent SQL injection
		$this->db->select('
            p.perkara_id,
            p.nomor_perkara,
            p.jenis_perkara_nama,
            pp.tanggal_putusan,
            pp.tanggal_minutasi,
            dd.created_date AS tanggal_publish,
            DATEDIFF(dd.created_date, pp.tanggal_putusan) AS selisih_hari,
            CASE 
                WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN "Ya"
                WHEN DATEDIFF(dd.created_date, pp.tanggal_putusan) <= 1 THEN "Ya (1 Hari)"
                ELSE "Tidak"
            END AS is_odp,
            dd.link_dirput,
            dd.filename
        ');

		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'inner');
		$this->db->join('dirput_dokumen dd', 'p.perkara_id = dd.perkara_id', 'inner');

		// Filter by year
		$this->db->where('YEAR(pp.tanggal_putusan)', $lap_tahun);

		// Filter by month if provided
		if (!empty($lap_bulan)) {
			$this->db->where('MONTH(pp.tanggal_putusan)', $lap_bulan);
		}

		// Filter only files that are anonymized decisions
		$this->db->where('dd.filename LIKE', '%anonimisasi%');

		// Order by decision date and case number
		$this->db->order_by('pp.tanggal_putusan', 'DESC');
		$this->db->order_by('p.nomor_perkara', 'ASC');

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Get ODP statistics for display on dashboard
	 * 
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year
	 * @return object Statistics data
	 */
	function get_odp_stats($lap_bulan = null, $lap_tahun)
	{
		// Build time filter condition
		if (!empty($lap_bulan)) {
			$time_condition = "YEAR(pp.tanggal_putusan) = ? AND MONTH(pp.tanggal_putusan) = ?";
			$params = array($lap_tahun, $lap_bulan);
		} else {
			$time_condition = "YEAR(pp.tanggal_putusan) = ?";
			$params = array($lap_tahun);
		}

		// Get ODP statistics using optimized query
		$sql = "SELECT 
                COUNT(DISTINCT p.perkara_id) AS total_putus,
                SUM(CASE WHEN dd.created_date IS NOT NULL THEN 1 ELSE 0 END) AS total_publish,
                SUM(CASE WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN 1 ELSE 0 END) AS total_odp_same_day,
                SUM(CASE WHEN DATEDIFF(dd.created_date, pp.tanggal_putusan) <= 1 THEN 1 ELSE 0 END) AS total_odp_one_day,
                ROUND(
                    SUM(CASE WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN 1 ELSE 0 END) / 
                    COUNT(DISTINCT p.perkara_id) * 100, 
                2) AS pct_odp_same_day,
                ROUND(
                    SUM(CASE WHEN DATEDIFF(dd.created_date, pp.tanggal_putusan) <= 1 THEN 1 ELSE 0 END) / 
                    COUNT(DISTINCT p.perkara_id) * 100, 
                2) AS pct_odp_one_day,
                AVG(DATEDIFF(dd.created_date, pp.tanggal_putusan)) AS avg_publish_days
            FROM 
                perkara p
                INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
                LEFT JOIN dirput_dokumen dd ON p.perkara_id = dd.perkara_id AND dd.filename LIKE '%anonimisasi%'
            WHERE 
                $time_condition";

		$query = $this->db->query($sql, $params);
		return $query->row();
	}

	/**
	 * Get monthly ODP performance for charts
	 * 
	 * @param string $lap_tahun Year
	 * @return array Monthly performance data
	 */
	function get_monthly_performance($lap_tahun)
	{
		$sql = "SELECT 
                MONTH(pp.tanggal_putusan) AS month_num,
                COUNT(DISTINCT p.perkara_id) AS total_putus,
                SUM(CASE WHEN dd.created_date IS NOT NULL THEN 1 ELSE 0 END) AS total_publish,
                SUM(CASE WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN 1 ELSE 0 END) AS total_odp_same_day,
                SUM(CASE WHEN DATEDIFF(dd.created_date, pp.tanggal_putusan) <= 1 THEN 1 ELSE 0 END) AS total_odp_one_day
            FROM 
                perkara p
                INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
                LEFT JOIN dirput_dokumen dd ON p.perkara_id = dd.perkara_id AND dd.filename LIKE '%anonimisasi%'
            WHERE 
                YEAR(pp.tanggal_putusan) = ?
            GROUP BY 
                MONTH(pp.tanggal_putusan)
            ORDER BY 
                MONTH(pp.tanggal_putusan)";

		$query = $this->db->query($sql, array($lap_tahun));
		return $query->result();
	}

	/**
	 * Get ODP cases by jenis_perkara for charts
	 * 
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year
	 * @return array Cases grouped by case type
	 */
	function get_perkara_distribution($lap_bulan = null, $lap_tahun)
	{
		// Build time filter condition
		if (!empty($lap_bulan)) {
			$time_condition = "YEAR(pp.tanggal_putusan) = ? AND MONTH(pp.tanggal_putusan) = ?";
			$params = array($lap_tahun, $lap_bulan);
		} else {
			$time_condition = "YEAR(pp.tanggal_putusan) = ?";
			$params = array($lap_tahun);
		}

		$sql = "SELECT 
                p.jenis_perkara_nama,
                COUNT(DISTINCT p.perkara_id) AS total_cases,
                SUM(CASE WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN 1 ELSE 0 END) AS total_odp_same_day,
                ROUND(
                    SUM(CASE WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN 1 ELSE 0 END) / 
                    COUNT(DISTINCT p.perkara_id) * 100, 
                2) AS pct_odp
            FROM 
                perkara p
                INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
                LEFT JOIN dirput_dokumen dd ON p.perkara_id = dd.perkara_id AND dd.filename LIKE '%anonimisasi%'
            WHERE 
                $time_condition
            GROUP BY 
                p.jenis_perkara_nama
            ORDER BY
                total_cases DESC";

		$query = $this->db->query($sql, $params);
		return $query->result();
	}

	/**
	 * Get detailed information about a specific case
	 * 
	 * @param int $perkara_id Case ID
	 * @return object Detailed case information
	 */
	public function get_detail($perkara_id)
	{
		$this->db->select('
			p.perkara_id, 
			p.nomor_perkara, 
			p.jenis_perkara_nama,
			p.tanggal_pendaftaran,
			pp.tanggal_putusan,
			pp.tanggal_minutasi,
			pp.amar_putusan,
			pp.status_putusan_nama,
			pp.status_putusan_text,
			pp.tanggal_bht,
			ppen.majelis_hakim_nama,
			ppen.panitera_pengganti_text,
			ppi1.nama AS nama_p,
			ph1.alamat AS alamat_p,
			ph1.telepon AS telepon_p,
			ppi2.nama AS nama_t,
			ph2.alamat AS alamat_t,
			ph2.telepon AS telepon_t,
			dd.created_date AS tanggal_publish,
			dd.filename,
			dd.link_dirput,
			DATEDIFF(dd.created_date, pp.tanggal_putusan) AS selisih_hari,
			CASE 
				WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN "Ya"
				WHEN DATEDIFF(dd.created_date, pp.tanggal_putusan) <= 1 THEN "Ya (1 Hari)"
				ELSE "Tidak"
			END AS is_odp
		');

		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_penetapan ppen', 'p.perkara_id = ppen.perkara_id', 'left');
		$this->db->join('perkara_pihak1 ppi1', 'p.perkara_id = ppi1.perkara_id AND ppi1.urutan = 1', 'left');
		$this->db->join('pihak ph1', 'ppi1.pihak_id = ph1.id', 'left');
		$this->db->join('perkara_pihak2 ppi2', 'p.perkara_id = ppi2.perkara_id AND ppi2.urutan = 1', 'left');
		$this->db->join('pihak ph2', 'ppi2.pihak_id = ph2.id', 'left');
		$this->db->join('dirput_dokumen dd', 'p.perkara_id = dd.perkara_id', 'left');
		$this->db->where('p.perkara_id', $perkara_id);

		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * Get documents related to a case
	 * 
	 * @param int $perkara_id Case ID
	 * @return array List of documents
	 */
	public function get_documents($perkara_id)
	{
		$this->db->select('
			dd.id,
			dd.filename AS nama_dokumen,
			dd.created_date AS tanggal,
			dd.link_dirput AS link_dokumen,
			"Tersedia" AS status
		');

		$this->db->from('dirput_dokumen dd');
		$this->db->where('dd.perkara_id', $perkara_id);
		$this->db->order_by('dd.created_date', 'DESC');

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Get hearing schedule for a case
	 * 
	 * @param int $perkara_id Case ID
	 * @return array Hearing schedule
	 */
	public function get_hearings($perkara_id)
	{
		try {
			// Check if ruangan table exists
			$ruangan_exists = $this->db->table_exists('ruangan');

			// Build the appropriate SQL query based on whether the ruangan table exists
			if ($ruangan_exists) {
				$this->db->select('
					js.urutan,
					js.tanggal_sidang,
					js.jam_sidang,
					js.agenda,
					r.nama AS ruangan,
					CASE 
						WHEN js.ditunda = "Y" THEN "Tunda"
						WHEN js.dihadiri_oleh IS NOT NULL THEN "Selesai"
						ELSE "Jadwal" 
					END AS status_sidang
				');

				$this->db->from('perkara_jadwal_sidang js');
				$this->db->join('ruangan r', 'js.ruangan_id = r.id', 'left');
			} else {
				// Modified query without joining to ruangan table
				$this->db->select('
					js.urutan,
					js.tanggal_sidang,
					js.jam_sidang,
					js.agenda,
					CONCAT("Ruangan ", js.ruangan_id) AS ruangan,
					CASE 
						WHEN js.ditunda = "Y" THEN "Tunda"
						WHEN js.dihadiri_oleh IS NOT NULL THEN "Selesai"
						ELSE "Jadwal" 
					END AS status_sidang
				', FALSE); // FALSE to disable field escaping for the CASE statement

				$this->db->from('perkara_jadwal_sidang js');
			}

			$this->db->where('js.perkara_id', $perkara_id);
			$this->db->order_by('js.tanggal_sidang', 'ASC');

			$query = $this->db->get();
			return $query->result();
		} catch (Exception $e) {
			// Fallback query if there are any issues
			$sql = "SELECT 
				urutan,
				tanggal_sidang,
				jam_sidang,
				agenda,
				CONCAT('Ruangan ', ruangan_id) AS ruangan,
				CASE 
					WHEN ditunda = 'Y' THEN 'Tunda'
					WHEN dihadiri_oleh IS NOT NULL THEN 'Selesai'
					ELSE 'Jadwal' 
				END AS status_sidang
			FROM perkara_jadwal_sidang 
			WHERE perkara_id = ?
			ORDER BY tanggal_sidang ASC";

			$query = $this->db->query($sql, array($perkara_id));
			return $query->result();
		}
	}

	/**
	 * Get ODP data for export
	 * 
	 * @param string $lap_bulan Month (01-12) or null for all months
	 * @param string $lap_tahun Year
	 * @return array Array of ODP data
	 */
	public function get_odp_data($lap_bulan, $lap_tahun)
	{
		// Sanitize input
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build date condition
		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_condition = "YEAR(pp.tanggal_putusan) = '$lap_tahun' AND MONTH(pp.tanggal_putusan) = '$lap_bulan'";
		} else {
			$date_condition = "YEAR(pp.tanggal_putusan) = '$lap_tahun'";
		}

		// Query for ODP data
		$sql = "SELECT 
				p.perkara_id,
				p.nomor_perkara,
				p.jenis_perkara_nama,
				pp.tanggal_putusan,
				pp.tanggal_minutasi,
				dd.created_date AS tanggal_publish,
				DATEDIFF(dd.created_date, pp.tanggal_putusan) AS selisih_hari,
				CASE 
					WHEN DATE(dd.created_date) = DATE(pp.tanggal_putusan) THEN 'Ya' 
					WHEN DATEDIFF(dd.created_date, pp.tanggal_putusan) <= 1 THEN 'Ya (1 Hari)'
					ELSE 'Tidak' 
				END AS is_odp,
				dd.filename
			FROM perkara p
			INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
			INNER JOIN dirput_dokumen dd ON p.perkara_id = dd.perkara_id
			WHERE $date_condition
			ORDER BY pp.tanggal_putusan DESC";

		$query = $this->db->query($sql);
		return $query->result();
	}
}
