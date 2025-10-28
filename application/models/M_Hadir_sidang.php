<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Hadir_sidang extends CI_Model
{
	function hadir_sidang($lap_bulan, $lap_tahun)
	{
		$query = $this->db->query("SELECT nomor_perkara, pihak1_text, pihak2_text, tanggal_sidang, dihadiri_oleh, panitera_pengganti_text
			FROM v_perkara, perkara_jadwal_sidang
			WHERE v_perkara.`perkara_id`=perkara_jadwal_sidang.`perkara_id`
			AND YEAR(perkara_jadwal_sidang.`tanggal_sidang`)='$lap_tahun' AND MONTH(perkara_jadwal_sidang.`tanggal_sidang`)='$lap_bulan'
			ORDER BY tanggal_sidang");
		return $query->result();
	}
}