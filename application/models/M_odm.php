<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_odm extends CI_Model
{
	/**
	 * Mendapatkan data One Day Minute dengan optimasi
	 * 
	 * @param string $lap_bulan Bulan (01-12)
	 * @param string $lap_tahun Tahun
	 * @return array Data OD Minute
	 */
	function get_odm_data($lap_bulan, $lap_tahun)
	{
		// Sanitasi input untuk keamanan
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Gunakan prepared statement dan pilih kolom yang dibutuhkan saja
		$sql = "SELECT 
                p.perkara_id,
                p.nomor_perkara,
                p.jenis_perkara_nama,
                pp.tanggal_putusan,
                pp.tanggal_minutasi,
                DATEDIFF(pp.tanggal_minutasi, pp.tanggal_putusan) AS selisih_hari,
                CASE 
                    WHEN DATE(pp.tanggal_minutasi) = DATE(pp.tanggal_putusan) THEN 'Ya' 
                    ELSE 'Tidak' 
                END AS is_odm
            FROM 
                perkara p
                INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id 
            WHERE 
                YEAR(pp.tanggal_minutasi) = ? 
                AND MONTH(pp.tanggal_minutasi) = ?
                AND pp.tanggal_minutasi IS NOT NULL
            ORDER BY 
                pp.tanggal_minutasi DESC";

		// Gunakan query binding untuk keamanan
		$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));
		return $query->result();
	}

	/**
	 * Mendapatkan statistik One Day Minute
	 * 
	 * @param string $lap_bulan Bulan (01-12)
	 * @param string $lap_tahun Tahun
	 * @return object Data statistik
	 */
	function get_statistics($lap_bulan, $lap_tahun)
	{
		// Sanitasi input
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Query untuk statistik dasar dengan satu query (lebih efisien)
		$sql = "SELECT 
                COUNT(*) AS total_count,
                SUM(CASE WHEN DATE(pp.tanggal_minutasi) = DATE(pp.tanggal_putusan) THEN 1 ELSE 0 END) AS odm_count,
                AVG(DATEDIFF(pp.tanggal_minutasi, pp.tanggal_putusan)) AS avg_days,
                COUNT(CASE WHEN DATEDIFF(pp.tanggal_minutasi, pp.tanggal_putusan) > 0 THEN 1 END) AS delay_count,
                MAX(DATEDIFF(pp.tanggal_minutasi, pp.tanggal_putusan)) AS max_delay
            FROM 
                perkara p
                INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id 
            WHERE 
                YEAR(pp.tanggal_minutasi) = ? 
                AND MONTH(pp.tanggal_minutasi) = ?
                AND pp.tanggal_minutasi IS NOT NULL";

		$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));
		return $query->row();
	}

	/**
	 * Mendapatkan distribusi jenis perkara untuk ODM
	 * 
	 * @param string $lap_bulan Bulan (01-12)
	 * @param string $lap_tahun Tahun
	 * @return array Distribusi jenis perkara
	 */
	function get_jenis_perkara_distribution($lap_bulan, $lap_tahun)
	{
		// Sanitasi input
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Query untuk distribusi jenis perkara dengan agregasi di database
		$sql = "SELECT 
                p.jenis_perkara_nama,
                COUNT(*) AS total,
                SUM(CASE WHEN DATE(pp.tanggal_minutasi) = DATE(pp.tanggal_putusan) THEN 1 ELSE 0 END) AS odm_count
            FROM 
                perkara p
                INNER JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id 
            WHERE 
                YEAR(pp.tanggal_minutasi) = ? 
                AND MONTH(pp.tanggal_minutasi) = ?
                AND pp.tanggal_minutasi IS NOT NULL
            GROUP BY 
                p.jenis_perkara_nama
            ORDER BY 
                total DESC
            LIMIT 10"; // Batasi hanya 10 jenis perkara teratas

		$query = $this->db->query($sql, array($lap_tahun, $lap_bulan));
		return $query->result();
	}

	/**
	 * Export ODM data to Excel format
	 * 
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return void Outputs Excel file for download
	 */
	public function export_excel($lap_bulan, $lap_tahun)
	{
		// Sanitize input
		$lap_bulan = $this->db->escape_str($lap_bulan);
		$lap_tahun = $this->db->escape_str($lap_tahun);

		// Get ODM data
		$data = $this->get_odm_data($lap_bulan, $lap_tahun);

		// Define month names for display
		$nama_bulan = array(
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember'
		);

		// Set filename
		$month_label = isset($nama_bulan[$lap_bulan]) ? $nama_bulan[$lap_bulan] : 'Semua_Bulan';
		$filename = "Data_ODM_{$month_label}_{$lap_tahun}_" . date('Ymd_His') . ".xls";

		// Set header for Excel download
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Cache-Control: max-age=0");

		// Create Excel content (HTML table with Excel compatibility)
		echo "
		<html xmlns:o='urn:schemas-microsoft-com:office:office' 
			  xmlns:x='urn:schemas-microsoft-com:office:excel' 
			  xmlns='http://www.w3.org/TR/REC-html40'>
		<head>
			<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
			<style>
				table {
					border-collapse: collapse;
					width: 100%;
				}
				th, td {
					border: 1px solid #000000;
					padding: 8px;
					text-align: left;
				}
				th {
					background-color: #4CAF50;
					color: white;
					font-weight: bold;
				}
				.txt-center {
					text-align: center;
				}
				h3 {
					text-align: center;
				}
				.bg-success {
					background-color: #DFF0D8;
				}
				.bg-warning {
					background-color: #FCF8E3;
				}
				.bg-danger {
					background-color: #F2DEDE;
				}
			</style>
		</head>
		<body>
			<h3>Data One Day Minute (ODM) " . (isset($nama_bulan[$lap_bulan]) ? $nama_bulan[$lap_bulan] . ' ' : '') . "$lap_tahun</h3>
			<p>Tanggal Export: " . date('d-m-Y H:i:s') . "</p>
			
			<table border='1'>
				<thead>
					<tr>
						<th class='txt-center'>No</th>
						<th>Nomor Perkara</th>
						<th>Jenis Perkara</th>
						<th>Tanggal Putus</th>
						<th>Tanggal Minutasi</th>
						<th class='txt-center'>Selisih (hari)</th>
						<th>Status ODM</th>
					</tr>
				</thead>
				<tbody>";

		$no = 1;
		foreach ($data as $row) {
			// Calculate days difference
			$putus_date = new DateTime($row->tanggal_putusan);
			$minutasi_date = new DateTime($row->tanggal_minutasi);
			$interval = $putus_date->diff($minutasi_date);
			$days = $interval->days;

			// Determine if it's ODM
			$is_odm = (date('Y-m-d', strtotime($row->tanggal_putusan)) === date('Y-m-d', strtotime($row->tanggal_minutasi)));

			// Set row class based on days difference
			$row_class = $is_odm ? 'bg-success' : ($days <= 7 ? 'bg-warning' : 'bg-danger');

			// Set status text
			$status = $is_odm ? 'One Day Minute' : ($days <= 7 ? 'Dalam Batas Waktu' : 'Terlambat');

			echo "<tr class='$row_class'>
					<td class='txt-center'>$no</td>
					<td>$row->nomor_perkara</td>
					<td>$row->jenis_perkara_nama</td>
					<td>" . date('d-m-Y', strtotime($row->tanggal_putusan)) . "</td>
					<td>" . date('d-m-Y', strtotime($row->tanggal_minutasi)) . "</td>
					<td class='txt-center'>$days</td>
					<td>$status</td>
				</tr>";
			$no++;
		}

		echo "
				</tbody>
			</table>
			
			<p>Total data: " . count($data) . "</p>
		</body>
		</html>";
		exit;
	}
}
