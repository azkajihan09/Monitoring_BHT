<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Prodeo extends CI_Model
{
	/**
	 * Get Prodeo cases with optimized query
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return array Array of Prodeo cases
	 */
	function prodeo($jenis_perkara, $lap_bulan, $lap_tahun)
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

		// Optimize query with proper JOIN clauses and aliasing
		$sql = "SELECT 
				p.perkara_id,
				p.nomor_perkara, 
				p.jenis_perkara_nama,
				pp.majelis_hakim_nama, 
				pp.panitera_pengganti_text, 
				p.tanggal_pendaftaran,
				pp.penetapan_majelis_hakim, 
				pp.penetapan_hari_sidang, 
				pp.sidang_pertama, 
				put.tanggal_putusan, 
				put.status_putusan_nama,
				put.tanggal_minutasi,
				p.prodeo,
				CASE 
					WHEN p.prodeo = 1 THEN 'Prodeo (Cuma-Cuma)'
					ELSE 'Biaya Normal'
				END as status_prodeo,
				ph1.nama AS nama_penggugat,
				ph1.alamat AS alamat_penggugat,
				ph2.nama AS nama_tergugat,
				ph2.alamat AS alamat_tergugat,
				DATEDIFF(IFNULL(put.tanggal_putusan, CURDATE()), p.tanggal_pendaftaran) as durasi_perkara
			FROM 
				perkara p
				LEFT JOIN perkara_penetapan pp ON p.perkara_id = pp.perkara_id
				LEFT JOIN perkara_putusan put ON p.perkara_id = put.perkara_id
				LEFT JOIN perkara_pihak1 pph1 ON p.perkara_id = pph1.perkara_id AND pph1.urutan = 1
				LEFT JOIN pihak ph1 ON pph1.pihak_id = ph1.id
				LEFT JOIN perkara_pihak2 pph2 ON p.perkara_id = pph2.perkara_id AND pph2.urutan = 1
				LEFT JOIN pihak ph2 ON pph2.pihak_id = ph2.id
			WHERE 
				$date_condition
				AND p.nomor_perkara LIKE ?
				AND p.prodeo = 1
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
	 * Get statistics for Prodeo cases
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return object Statistics for Prodeo cases
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

		// Get total cases for the period (for percentage calculation)
		$total_query = "SELECT 
				COUNT(*) as total_cases
			FROM 
				perkara p
				LEFT JOIN perkara_penetapan pp ON p.perkara_id = pp.perkara_id
				LEFT JOIN perkara_putusan put ON p.perkara_id = put.perkara_id
			WHERE 
				$date_condition
				AND p.nomor_perkara LIKE ?";

		$total_params = array(
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

		$total_result = $this->db->query($total_query, $total_params)->row();
		$total_cases = $total_result->total_cases;

		// Query for statistics
		$sql = "SELECT 
				COUNT(*) as total_prodeo,
				SUM(CASE WHEN put.tanggal_putusan IS NOT NULL THEN 1 ELSE 0 END) as completed_cases,
				SUM(CASE WHEN put.tanggal_putusan IS NULL THEN 1 ELSE 0 END) as ongoing_cases,
				AVG(DATEDIFF(IFNULL(put.tanggal_putusan, CURDATE()), p.tanggal_pendaftaran)) as avg_duration,
				MAX(DATEDIFF(IFNULL(put.tanggal_putusan, CURDATE()), p.tanggal_pendaftaran)) as max_duration,
				MIN(CASE WHEN put.tanggal_putusan IS NOT NULL THEN DATEDIFF(put.tanggal_putusan, p.tanggal_pendaftaran) ELSE NULL END) as min_duration,
				COUNT(DISTINCT pp.majelis_hakim_kode) as total_judges,
				COUNT(DISTINCT pp.panitera_pengganti_id) as total_clerks,
				SUM(CASE WHEN p.jenis_perkara_nama LIKE '%Cerai Gugat%' THEN 1 ELSE 0 END) as cerai_gugat_count,
				SUM(CASE WHEN p.jenis_perkara_nama LIKE '%Cerai Talak%' THEN 1 ELSE 0 END) as cerai_talak_count,
				SUM(CASE WHEN p.jenis_perkara_nama NOT LIKE '%Cerai%' THEN 1 ELSE 0 END) as other_case_count
			FROM 
				perkara p
				LEFT JOIN perkara_penetapan pp ON p.perkara_id = pp.perkara_id
				LEFT JOIN perkara_putusan put ON p.perkara_id = put.perkara_id
			WHERE 
				$date_condition
				AND p.nomor_perkara LIKE ?
				AND p.prodeo = 1";

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
		$result = $query->row();

		// Calculate percentage of prodeo cases from total cases
		if ($total_cases > 0) {
			$result->percent_of_total = ($result->total_prodeo / $total_cases) * 100;
		} else {
			$result->percent_of_total = 0;
		}

		return $result;
	}

	/**
	 * Export Prodeo cases to Excel
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return array Array of Prodeo cases
	 */
	function export_data($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Get the same data as the prodeo function but with additional fields for export
		return $this->prodeo($jenis_perkara, $lap_bulan, $lap_tahun);
	}

	/**
	 * Get detailed fee analysis for prodeo cases
	 * Shows what fees would have been if the cases weren't free
	 * 
	 * @param string $jenis_perkara Case type
	 * @param string $lap_bulan Month 
	 * @param string $lap_tahun Year
	 * @return array Fee data
	 */
	function get_biaya_detail($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Sanitize inputs
		$jenis_perkara = $this->db->escape_str($jenis_perkara);
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// 1. Get average fees from regular cases with the same case type
		$sql_regular_cases = "SELECT 
			AVG(total_biaya) as avg_biaya,
			MIN(total_biaya) as min_biaya,
			MAX(total_biaya) as max_biaya,
			COUNT(DISTINCT perkara_id) as total_cases
		FROM (
			SELECT 
				p.perkara_id,
				SUM(pb.jumlah) as total_biaya
			FROM 
				perkara p
				INNER JOIN perkara_biaya pb ON p.perkara_id = pb.perkara_id
			WHERE 
				p.prodeo = 0
				AND p.nomor_perkara LIKE '%$jenis_perkara%'
				AND ((YEAR(p.tanggal_pendaftaran)='$lap_tahun' 
					AND MONTH(p.tanggal_pendaftaran)='$lap_bulan')
					OR YEAR(p.tanggal_pendaftaran)='$lap_tahun')
			GROUP BY 
				p.perkara_id
		) as fee_totals";

		$query_regular = $this->db->query($sql_regular_cases);
		$regular_fees = $query_regular->row();

		// 2. Get breakdown of fee components
		$sql_components = "SELECT 
			jb.id as komponen_id,
			jb.nama as nama_komponen,
			AVG(pb.jumlah) as rata_rata,
			MIN(pb.jumlah) as minimal,
			MAX(pb.jumlah) as maksimal,
			COUNT(pb.id) as jumlah_kasus
		FROM 
			perkara p
			INNER JOIN perkara_biaya pb ON p.perkara_id = pb.perkara_id
			INNER JOIN jenis_biaya jb ON pb.jenis_biaya_id = jb.id
		WHERE 
			p.prodeo = 0
			AND p.nomor_perkara LIKE '%$jenis_perkara%'
			AND ((YEAR(p.tanggal_pendaftaran)='$lap_tahun' 
				AND MONTH(p.tanggal_pendaftaran)='$lap_bulan')
				OR YEAR(p.tanggal_pendaftaran)='$lap_tahun')
		GROUP BY 
			jb.id, jb.nama
		ORDER BY 
			rata_rata DESC";

		$query_components = $this->db->query($sql_components);

		// 3. Count how many prodeo cases in the period
		$sql_prodeo_count = "SELECT 
			COUNT(*) as jumlah_prodeo 
		FROM 
			perkara p
		WHERE 
			p.prodeo = 1
			AND p.nomor_perkara LIKE '%$jenis_perkara%'
			AND YEAR(p.tanggal_pendaftaran)='$lap_tahun' 
			AND MONTH(p.tanggal_pendaftaran)='$lap_bulan'";

		$query_prodeo = $this->db->query($sql_prodeo_count);
		$prodeo_count = $query_prodeo->row()->jumlah_prodeo;

		// Compile result
		$result = array(
			'regular_fees' => $regular_fees,
			'components' => $query_components->result(),
			'prodeo_count' => $prodeo_count
		);

		// Calculate total savings
		if ($regular_fees && $regular_fees->avg_biaya) {
			$result['total_savings'] = $regular_fees->avg_biaya * $prodeo_count;
		} else {
			// If no data available, use default estimate
			$estimated_avg_fee = 850000; // Rp. 850,000 estimate
			$result['total_savings'] = $estimated_avg_fee * $prodeo_count;
			$result['is_estimated'] = true;
		}

		return $result;
	}
}
