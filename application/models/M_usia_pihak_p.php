<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_usia_pihak_p extends CI_Model
{
	function usia_pihak_p($jenis_kelamin, $lap_bulan, $lap_tahun)
	{
		$query = $this->db->query("SELECT nomor_perkara, tanggal_pendaftaran, perkara_pihak1.`nama` as nama,  tanggal_lahir, TIMESTAMPDIFF(YEAR,tanggal_lahir,tanggal_pendaftaran) as usia, jenis_kelamin
			FROM perkara
			INNER JOIN perkara_pihak1 ON perkara.`perkara_id`=perkara_pihak1.`perkara_id`
			INNER JOIN pihak ON perkara_pihak1.`pihak_id`=pihak.`id`
			WHERE YEAR(tanggal_pendaftaran)='$lap_tahun' 
			AND MONTH(tanggal_pendaftaran)='$lap_bulan' 
			AND urutan = 1
			AND jenis_kelamin = '$jenis_kelamin'
			ORDER BY perkara.`perkara_id`
			");
		return $query->result();

	}
}