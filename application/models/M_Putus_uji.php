<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_putus_uji extends CI_Model
{
	function putus($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		$query = $this->db->query("SELECT majelis_hakim_nama, COUNT(majelis_hakim_nama) AS putus
		FROM perkara, perkara_penetapan, perkara_putusan
		WHERE perkara.`perkara_id`=perkara_penetapan.`perkara_id` 
		AND perkara.`perkara_id`=perkara_putusan.`perkara_id`
		AND YEAR(tanggal_putusan)='$lap_tahun' AND MONTH(tanggal_putusan)='$lap_bulan' 
		AND nomor_perkara LIKE '%$jenis_perkara%'
		GROUP BY majelis_hakim_nama
		ORDER BY putus DESC");
		return $query->result();
	}
	
	// Fungsi untuk mendapatkan statistik lengkap
	function get_statistics($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Total semua perkara putus
		$total_query = $this->db->query("SELECT COUNT(*) as total_perkara
		FROM perkara, perkara_penetapan, perkara_putusan
		WHERE perkara.`perkara_id`=perkara_penetapan.`perkara_id` 
		AND perkara.`perkara_id`=perkara_putusan.`perkara_id`
		AND YEAR(tanggal_putusan)='$lap_tahun' AND MONTH(tanggal_putusan)='$lap_bulan' 
		AND nomor_perkara LIKE '%$jenis_perkara%'");
		
		// Beban tertinggi dan terendah per majelis
		$beban_query = $this->db->query("SELECT 
			MAX(putus_count) as beban_tertinggi,
			MIN(putus_count) as beban_terendah,
			COUNT(*) as total_majelis
		FROM (
			SELECT majelis_hakim_nama, COUNT(majelis_hakim_nama) AS putus_count
			FROM perkara, perkara_penetapan, perkara_putusan
			WHERE perkara.`perkara_id`=perkara_penetapan.`perkara_id` 
			AND perkara.`perkara_id`=perkara_putusan.`perkara_id`
			AND YEAR(tanggal_putusan)='$lap_tahun' AND MONTH(tanggal_putusan)='$lap_bulan' 
			AND nomor_perkara LIKE '%$jenis_perkara%'
			GROUP BY majelis_hakim_nama
		) as subquery");
		
		$result = array(
			'total_perkara' => $total_query->row()->total_perkara,
			'beban_tertinggi' => $beban_query->row()->beban_tertinggi ?: 0,
			'beban_terendah' => $beban_query->row()->beban_terendah ?: 0,
			'total_majelis' => $beban_query->row()->total_majelis ?: 0
		);
		
		return $result;
	}
}
