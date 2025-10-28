<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Persidangan extends CI_Model
{
	function jadwal_sidang_js($jurusita, $tanggal_sidang)
	{
		
		$query = $this->db->query("SELECT perkara.perkara_id AS idperkara, nomor_perkara, jurusita_nama, panitera_nama, perkara_pihak1.nama AS nama_p, perkara_pihak1.alamat AS alamat_p, perkara_pihak2.nama AS nama_t, perkara_pihak2.alamat AS alamat_t, tanggal_sidang, perkara_jadwal_sidang.urutan as sidang_ke, dihadiri_oleh, tanggal_putusan
			FROM perkara
			LEFT JOIN perkara_jurusita ON perkara.`perkara_id`=perkara_jurusita.`perkara_id`
			LEFT JOIN perkara_panitera_pn ON perkara.`perkara_id`=`perkara_panitera_pn`.`perkara_id`
			LEFT JOIN perkara_pihak1 ON perkara.`perkara_id` = perkara_pihak1.`perkara_id`
			LEFT JOIN perkara_pihak2 ON perkara.`perkara_id` = perkara_pihak2.`perkara_id`
			LEFT JOIN perkara_jadwal_sidang on perkara.perkara_id = perkara_jadwal_sidang.perkara_id
			LEFT JOIN perkara_putusan ON perkara.`perkara_id`=perkara_putusan.`perkara_id`
			WHERE `perkara_panitera_pn`.`aktif`='Y' AND `perkara_jurusita`.`aktif`='Y' AND tanggal_sidang='$tanggal_sidang' AND jurusita_nama = '$jurusita'");
		return $query->result();

	}
	public function detail_data($idperkara = NULL)
	{
		//$query = $this->db->get_where('perkara', array('perkara_id'=>$idperkara))->row();
		//return $query;
		$query = $this->db->query("SELECT perkara.perkara_id as idperkara, nomor_perkara, jurusita_nama, panitera_nama, perkara_pihak1.nama as nama_p, perkara_pihak1.alamat as alamat_p, perkara_pihak2.nama as nama_t, perkara_pihak2.alamat as alamat_t, tanggal_sidang, perkara_jadwal_sidang.urutan as sidang_ke
			FROM perkara, perkara_jurusita, perkara_panitera_pn, perkara_jadwal_sidang, perkara_pihak1, perkara_pihak2
			WHERE perkara.perkara_id=perkara_jurusita.perkara_id AND perkara.perkara_id=perkara_panitera_pn.perkara_id AND perkara.perkara_id=perkara_jadwal_sidang.perkara_id
			AND perkara_jurusita.aktif='Y' AND perkara_panitera_pn.aktif='Y' AND perkara_pihak1.perkara_id=perkara.perkara_id AND perkara_pihak2.perkara_id=perkara.perkara_id
			AND perkara.perkara_id = '$idperkara'");
		return $query->row();		
	}
}
