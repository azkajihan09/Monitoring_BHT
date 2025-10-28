<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Penyerahan_ac extends CI_Model
{
	/**
	 * Ambil data penyerahan akta cerai berdasarkan bulan dan tahun atau tahun saja
	 */
	function penyerahan_ac($lap_bulan, $lap_tahun)
	{
		$lap_tahun = $this->db->escape_str($lap_tahun);

		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_filter = "(YEAR(pac.tgl_penyerahan_akta_cerai) = '$lap_tahun' AND MONTH(pac.tgl_penyerahan_akta_cerai) = '$lap_bulan')
				OR (YEAR(pac.tgl_penyerahan_akta_cerai_pihak2) = '$lap_tahun' AND MONTH(pac.tgl_penyerahan_akta_cerai_pihak2) = '$lap_bulan')";
		} else {
			$date_filter = "(YEAR(pac.tgl_penyerahan_akta_cerai) = '$lap_tahun' OR YEAR(pac.tgl_penyerahan_akta_cerai_pihak2) = '$lap_tahun')";
		}

		$sql = "SELECT 
			p.nomor_perkara,
			p.jenis_perkara_nama,
			pac.nomor_akta_cerai,
			pp.tanggal_putusan,
			pit.tgl_ikrar_talak,
			pp.tanggal_bht,
			pac.tgl_penyerahan_akta_cerai as tgl_AC_P,
			pac.tgl_penyerahan_akta_cerai_pihak2 as tgl_AC_T,
			pp1.nama as nama_p,
			pp2.nama as nama_t
		FROM perkara p
		LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
		LEFT JOIN perkara_ikrar_talak pit ON p.perkara_id = pit.perkara_id
		LEFT JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
		LEFT JOIN perkara_pihak1 pp1 ON p.perkara_id = pp1.perkara_id
		LEFT JOIN perkara_pihak2 pp2 ON p.perkara_id = pp2.perkara_id
		WHERE $date_filter
		ORDER BY p.perkara_id DESC";

		$query = $this->db->query($sql);
		return $query->result();
	}

	/**
	 * Statistik penyerahan akta cerai
	 */
	function getStatistics($lap_bulan, $lap_tahun)
	{
		$lap_tahun = $this->db->escape_str($lap_tahun);

		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_filter = "(YEAR(tgl_penyerahan_akta_cerai) = '$lap_tahun' AND MONTH(tgl_penyerahan_akta_cerai) = '$lap_bulan')
				OR (YEAR(tgl_penyerahan_akta_cerai_pihak2) = '$lap_tahun' AND MONTH(tgl_penyerahan_akta_cerai_pihak2) = '$lap_bulan')";
		} else {
			$date_filter = "(YEAR(tgl_penyerahan_akta_cerai) = '$lap_tahun' OR YEAR(tgl_penyerahan_akta_cerai_pihak2) = '$lap_tahun')";
		}

		$sql = "SELECT
			COUNT(*) as total,
			SUM(CASE WHEN (jenis_perkara_nama = 'Cerai Talak' AND tgl_penyerahan_akta_cerai IS NOT NULL) OR (jenis_perkara_nama = 'Cerai Gugat' AND tgl_penyerahan_akta_cerai_pihak2 IS NOT NULL) THEN 1 ELSE 0 END) as total_suami,
			SUM(CASE WHEN (jenis_perkara_nama = 'Cerai Talak' AND tgl_penyerahan_akta_cerai_pihak2 IS NOT NULL) OR (jenis_perkara_nama = 'Cerai Gugat' AND tgl_penyerahan_akta_cerai IS NOT NULL) THEN 1 ELSE 0 END) as total_istri
			FROM perkara
			LEFT JOIN perkara_akta_cerai ON perkara.perkara_id = perkara_akta_cerai.perkara_id
			WHERE $date_filter";
		$query = $this->db->query($sql);
		return $query->row();
	}
}
