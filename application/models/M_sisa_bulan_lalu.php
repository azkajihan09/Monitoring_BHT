<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_sisa_bulan_lalu extends CI_Model
{
	function sisa_bulan_lalu($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		$query = $this->db->query("SELECT majelis_hakim_nama, COUNT(majelis_hakim_nama) AS sisa_bulan_lalu
		FROM perkara
		INNER JOIN perkara_penetapan ON perkara.`perkara_id`=perkara_penetapan.`perkara_id`
		LEFT JOIN perkara_putusan ON perkara.`perkara_id` = perkara_putusan.`perkara_id`
		WHERE (tanggal_putusan IS NULL OR(YEAR(tanggal_putusan)='$lap_tahun' AND MONTH(tanggal_putusan)='$lap_bulan')) AND tanggal_pendaftaran<'$lap_tahun-$lap_bulan-01'
		AND nomor_perkara LIKE '%$jenis_perkara%'
		GROUP BY majelis_hakim_nama
		");
		return $query->result();

	}
}