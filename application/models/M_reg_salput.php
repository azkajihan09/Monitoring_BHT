<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_reg_salput extends CI_Model
{
	function reg_salput($lap_bulan, $lap_tahun)
	{
		$query = $this->db->query("SELECT nomor_perkara, tanggal_putusan, pihak1_text, a.`alamat` as alamat_p, pihak2_text, b.`alamat` as alamat_t, no_kutipan_akta_nikah, tgl_kutipan_akta_nikah, `tgl_ikrar_talak`, `tgl_akta_cerai` FROM perkara
			INNER JOIN perkara_data_pernikahan ON perkara.`perkara_id`=perkara_data_pernikahan.`perkara_id`
			INNER JOIN perkara_putusan ON perkara.`perkara_id`=perkara_putusan.`perkara_id`
			INNER JOIN perkara_akta_cerai ON perkara.`perkara_id`=perkara_akta_cerai.`perkara_id`
			INNER JOIN perkara_pihak1 ON perkara.`perkara_id`=perkara_pihak1.`perkara_id`
			INNER JOIN perkara_pihak2 ON perkara.`perkara_id`=perkara_pihak2.`perkara_id`
			INNER JOIN pihak a ON perkara_pihak1.`pihak_id`= a.`id`
			INNER JOIN pihak b ON perkara_pihak2.`pihak_id`= b.`id`
			LEFT JOIN `perkara_ikrar_talak` ON perkara.`perkara_id`=perkara_ikrar_talak.`perkara_id`
			WHERE YEAR(`tgl_akta_cerai`)='$lap_tahun' AND MONTH(`tgl_akta_cerai`)='$lap_bulan'
			ORDER BY nomor_urut_akta_cerai
		");
		return $query->result();
	}
}