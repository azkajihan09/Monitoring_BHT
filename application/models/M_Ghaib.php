<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Ghaib extends CI_Model
{
	/**
	 * Get ghaib cases with optimized query
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return array Array of ghaib cases
	 */
	function ghaib($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters for security
		$jenis_perkara = $this->db->escape_str($jenis_perkara);
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Optimize query to only select necessary columns and use proper JOIN conditions
		$sql = "SELECT 
                p.nomor_perkara, 
                pp.majelis_hakim_nama, 
                pp.panitera_pengganti_text, 
                p.tanggal_pendaftaran,
                pp.penetapan_majelis_hakim, 
                pp.penetapan_hari_sidang, 
                pp.sidang_pertama, 
                put.tanggal_putusan, 
                put.status_putusan_nama,
                p2.alamat as alamat_t,
                p2.ghaib
            FROM 
                perkara p
                LEFT JOIN perkara_penetapan pp ON p.perkara_id = pp.perkara_id
                LEFT JOIN (
                    SELECT 
                        perkara_id, 
                        MAX(tanggal_putusan) as latest_date 
                    FROM 
                        perkara_putusan 
                    GROUP BY 
                        perkara_id
                ) latest_put ON p.perkara_id = latest_put.perkara_id
                LEFT JOIN perkara_putusan put ON latest_put.perkara_id = put.perkara_id AND latest_put.latest_date = put.tanggal_putusan
                LEFT JOIN perkara_pihak2 p2 ON p.perkara_id = p2.perkara_id
            WHERE 
                (
                    (YEAR(p.tanggal_pendaftaran) = ? AND MONTH(p.tanggal_pendaftaran) = ?) OR
                    (YEAR(pp.penetapan_majelis_hakim) = ? AND MONTH(pp.penetapan_majelis_hakim) = ?) OR
                    (YEAR(pp.penetapan_hari_sidang) = ? AND MONTH(pp.penetapan_hari_sidang) = ?) OR
                    (YEAR(pp.sidang_pertama) = ? AND MONTH(pp.sidang_pertama) = ?) OR
                    (YEAR(put.tanggal_putusan) = ? AND MONTH(put.tanggal_putusan) = ?) OR
                    put.tanggal_putusan IS NULL
                )
                AND p.nomor_perkara LIKE ?
                AND (p2.alamat LIKE '%tidak diketahui%' OR p2.ghaib = 1)
            ORDER BY 
                p.tanggal_pendaftaran DESC";

		$params = [
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
		];

		$query = $this->db->query($sql, $params);
		return $query->result();
	}

	/**
	 * Get statistics for ghaib cases
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return object Statistics for ghaib cases
	 */
	function get_statistics($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters
		$jenis_perkara = $this->db->escape_str($jenis_perkara);
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Get total cases for the period (for percentage calculation)
		$total_query = "SELECT 
                COUNT(*) as total_cases
            FROM 
                perkara p
            WHERE 
                (YEAR(p.tanggal_pendaftaran) = ? AND MONTH(p.tanggal_pendaftaran) = ?)
                AND p.nomor_perkara LIKE ?";

		$total_result = $this->db->query($total_query, [$lap_tahun, $lap_bulan, '%' . $jenis_perkara . '%'])->row();
		$total_cases = $total_result->total_cases;

		// Get statistics for ghaib cases
		$sql = "SELECT 
                COUNT(*) as total_ghaib,
                SUM(CASE WHEN put.tanggal_putusan IS NOT NULL THEN 1 ELSE 0 END) as putus_count,
                SUM(CASE WHEN put.tanggal_putusan IS NULL THEN 1 ELSE 0 END) as in_process,
                AVG(
                    CASE 
                        WHEN put.tanggal_putusan IS NOT NULL 
                        THEN DATEDIFF(put.tanggal_putusan, p.tanggal_pendaftaran) 
                        ELSE NULL 
                    END
                ) as avg_days
            FROM 
                perkara p
                LEFT JOIN perkara_penetapan pp ON p.perkara_id = pp.perkara_id
                LEFT JOIN (
                    SELECT 
                        perkara_id, 
                        MAX(tanggal_putusan) as latest_date 
                    FROM 
                        perkara_putusan 
                    GROUP BY 
                        perkara_id
                ) latest_put ON p.perkara_id = latest_put.perkara_id
                LEFT JOIN perkara_putusan put ON latest_put.perkara_id = put.perkara_id AND latest_put.latest_date = put.tanggal_putusan
                LEFT JOIN perkara_pihak2 p2 ON p.perkara_id = p2.perkara_id
            WHERE 
                (
                    (YEAR(p.tanggal_pendaftaran) = ? AND MONTH(p.tanggal_pendaftaran) = ?) OR
                    (YEAR(pp.penetapan_majelis_hakim) = ? AND MONTH(pp.penetapan_majelis_hakim) = ?) OR
                    (YEAR(pp.penetapan_hari_sidang) = ? AND MONTH(pp.penetapan_hari_sidang) = ?) OR
                    (YEAR(pp.sidang_pertama) = ? AND MONTH(pp.sidang_pertama) = ?) OR
                    (YEAR(put.tanggal_putusan) = ? AND MONTH(put.tanggal_putusan) = ?) OR
                    put.tanggal_putusan IS NULL
                )
                AND p.nomor_perkara LIKE ?
                AND (p2.alamat LIKE '%tidak diketahui%' OR p2.ghaib = 1)";

		$params = [
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
		];

		$result = $this->db->query($sql, $params)->row();

		// Calculate percentage of ghaib cases from total cases
		if ($total_cases > 0) {
			$result->percent_of_total = ($result->total_ghaib / $total_cases) * 100;
		} else {
			$result->percent_of_total = 0;
		}

		return $result;
	}
}
