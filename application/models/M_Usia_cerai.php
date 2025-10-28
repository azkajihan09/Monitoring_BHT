<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Usia_cerai extends CI_Model
{
	function usia_cerai($lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build query conditions based on report type
		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun' AND MONTH(pac.tgl_akta_cerai) = '$lap_bulan'";
		} else {
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun'";
		}

		$sql = "SELECT 
            p.perkara_id,
            p.nomor_perkara, 
            p.tanggal_pendaftaran,
            pp.tanggal_putusan,
            TIMESTAMPDIFF(DAY, p.tanggal_pendaftaran, pp.tanggal_putusan) AS durasi_perkara,
            fp.nama as alasan,
            a.nama as nama_p, 
            c.tanggal_lahir as tanggal_lahir_p, 
            TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran) AS usia_p,
            c.jenis_kelamin as jenis_kelamin_p,
            c.agama_nama as agama_p,
            c.pekerjaan as pekerjaan_p,
            c.pendidikan as pendidikan_p,
            b.nama as nama_t, 
            d.tanggal_lahir as tanggal_lahir_t,
            TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran) AS usia_t,
            d.jenis_kelamin as jenis_kelamin_t,
            d.agama_nama as agama_t,
            d.pekerjaan as pekerjaan_t,
            d.pendidikan as pendidikan_t,
            pdp.tgl_nikah,
            CASE 
                WHEN pdp.tgl_nikah IS NOT NULL THEN TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran) 
                ELSE NULL 
            END AS lama_nikah,
            pac.jenis_cerai,
            pac.faktor_perceraian_id,
            pac.perceraian_ke,
            pac.tgl_akta_cerai
        FROM perkara p
        INNER JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
        INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        LEFT JOIN faktor_perceraian fp ON pac.faktor_perceraian_id = fp.id
        LEFT JOIN perkara_pihak1 a ON p.perkara_id = a.perkara_id
        LEFT JOIN perkara_pihak2 b ON p.perkara_id = b.perkara_id
        LEFT JOIN pihak c ON a.pihak_id = c.id
        LEFT JOIN pihak d ON b.pihak_id = d.id
        LEFT JOIN perkara_data_pernikahan pdp ON p.perkara_id = pdp.perkara_id
        WHERE $date_condition  
        ORDER BY pac.nomor_urut_akta_cerai";

		$query = $this->db->query($sql);
		return $query->result();
	}

	function get_statistics($lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build query conditions based on report type
		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun' AND MONTH(pac.tgl_akta_cerai) = '$lap_bulan'";
		} else {
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun'";
		}

		$sql = "SELECT
            COUNT(*) as total_perceraian,
            
            -- Statistik usia saat pengajuan perceraian
            AVG(TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran)) AS avg_usia_p,
            MIN(TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran)) AS min_usia_p,
            MAX(TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran)) AS max_usia_p,
            
            AVG(TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran)) AS avg_usia_t,
            MIN(TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran)) AS min_usia_t,
            MAX(TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran)) AS max_usia_t,
            
            -- Statistik durasi pernikahan
            AVG(CASE 
                WHEN pdp.tgl_nikah IS NOT NULL THEN TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran) 
                ELSE NULL 
            END) AS avg_lama_nikah,
            
            MAX(CASE 
                WHEN pdp.tgl_nikah IS NOT NULL THEN TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran) 
                ELSE NULL 
            END) AS max_lama_nikah,
            
            -- Statistik durasi proses perceraian
            AVG(TIMESTAMPDIFF(DAY, p.tanggal_pendaftaran, pp.tanggal_putusan)) AS avg_durasi_proses,
            
            -- Statistik jenis cerai
            SUM(CASE WHEN pac.jenis_cerai = 'Cerai Talak' THEN 1 ELSE 0 END) AS total_cerai_talak,
            SUM(CASE WHEN pac.jenis_cerai = 'Cerai Gugat' THEN 1 ELSE 0 END) AS total_cerai_gugat
            
        FROM perkara p
        INNER JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
        INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
        LEFT JOIN perkara_pihak1 a ON p.perkara_id = a.perkara_id
        LEFT JOIN perkara_pihak2 b ON p.perkara_id = b.perkara_id
        LEFT JOIN pihak c ON a.pihak_id = c.id
        LEFT JOIN pihak d ON b.pihak_id = d.id
        LEFT JOIN perkara_data_pernikahan pdp ON p.perkara_id = pdp.perkara_id
        WHERE $date_condition";

		$query = $this->db->query($sql);
		$result = $query->row();

		// Add faktor_perceraian property to the result
		$result->faktor_perceraian = $this->get_faktor_perceraian($lap_bulan, $lap_tahun);

		return $result;
	}

	/**
	 * Get factor data separately to avoid nested aggregates
	 */
	function get_faktor_perceraian($lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build query conditions based on report type
		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun' AND MONTH(pac.tgl_akta_cerai) = '$lap_bulan'";
		} else {
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun'";
		}

		$sql = "SELECT 
            fp.nama, 
            COUNT(*) as jumlah
        FROM perkara p
        INNER JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
        LEFT JOIN faktor_perceraian fp ON pac.faktor_perceraian_id = fp.id
        WHERE $date_condition
        AND fp.nama IS NOT NULL
        GROUP BY fp.nama
        ORDER BY jumlah DESC
        LIMIT 10";

		$query = $this->db->query($sql);
		$factors = $query->result();

		// Convert to string format for backward compatibility
		$result = '';
		foreach ($factors as $index => $factor) {
			$result .= $factor->nama . ':' . $factor->jumlah;
			if ($index < count($factors) - 1) {
				$result .= '|';
			}
		}

		return $result;
	}

	function get_usia_ranges($lap_bulan, $lap_tahun)
	{
		// Sanitize input parameters
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Build query conditions based on report type
		if (!empty($lap_bulan)) {
			$lap_bulan = $this->db->escape_str($lap_bulan);
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun' AND MONTH(pac.tgl_akta_cerai) = '$lap_bulan'";
		} else {
			$date_condition = "YEAR(pac.tgl_akta_cerai) = '$lap_tahun'";
		}

		$sql = "SELECT
            -- Range usia penggugat/pemohon
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran) < 20 THEN 1 ELSE 0 END) AS p_usia_dibawah_20,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) AS p_usia_20_30,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran) BETWEEN 31 AND 40 THEN 1 ELSE 0 END) AS p_usia_31_40,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran) BETWEEN 41 AND 50 THEN 1 ELSE 0 END) AS p_usia_41_50,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, c.tanggal_lahir, p.tanggal_pendaftaran) > 50 THEN 1 ELSE 0 END) AS p_usia_diatas_50,
            
            -- Range usia tergugat/termohon
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran) < 20 THEN 1 ELSE 0 END) AS t_usia_dibawah_20,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran) BETWEEN 20 AND 30 THEN 1 ELSE 0 END) AS t_usia_20_30,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran) BETWEEN 31 AND 40 THEN 1 ELSE 0 END) AS t_usia_31_40,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran) BETWEEN 41 AND 50 THEN 1 ELSE 0 END) AS t_usia_41_50,
            SUM(CASE WHEN TIMESTAMPDIFF(YEAR, d.tanggal_lahir, p.tanggal_pendaftaran) > 50 THEN 1 ELSE 0 END) AS t_usia_diatas_50,
            
            -- Range lamanya pernikahan
            SUM(CASE 
                WHEN pdp.tgl_nikah IS NOT NULL AND TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran) < 1 THEN 1 
                ELSE 0 
            END) AS nikah_kurang_1_tahun,
            SUM(CASE 
                WHEN pdp.tgl_nikah IS NOT NULL AND TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran) BETWEEN 1 AND 5 THEN 1 
                ELSE 0 
            END) AS nikah_1_5_tahun,
            SUM(CASE 
                WHEN pdp.tgl_nikah IS NOT NULL AND TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran) BETWEEN 6 AND 10 THEN 1 
                ELSE 0 
            END) AS nikah_6_10_tahun,
            SUM(CASE 
                WHEN pdp.tgl_nikah IS NOT NULL AND TIMESTAMPDIFF(YEAR, pdp.tgl_nikah, p.tanggal_pendaftaran) > 10 THEN 1 
                ELSE 0 
            END) AS nikah_lebih_10_tahun
            
        FROM perkara p
        INNER JOIN perkara_akta_cerai pac ON p.perkara_id = pac.perkara_id
        LEFT JOIN perkara_pihak1 a ON p.perkara_id = a.perkara_id
        LEFT JOIN perkara_pihak2 b ON p.perkara_id = b.perkara_id
        LEFT JOIN pihak c ON a.pihak_id = c.id
        LEFT JOIN pihak d ON b.pihak_id = d.id
        LEFT JOIN perkara_data_pernikahan pdp ON p.perkara_id = pdp.perkara_id
        WHERE $date_condition";

		$query = $this->db->query($sql);
		return $query->row();
	}
}
