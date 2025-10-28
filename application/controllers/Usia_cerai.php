<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Usia_cerai extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_Usia_cerai");
	}

	public function index()
	{
		$data = [];

		// Periksa adanya data form yang disubmit
		if ($this->input->post('jenis_laporan') || $this->input->post('lap_tahun')) {
			$data['jenis_laporan'] = $this->input->post('jenis_laporan', TRUE);
			$data['lap_tahun'] = $this->input->post('lap_tahun', TRUE);

			// If annual report is selected, set month to null or use all months
			if ($data['jenis_laporan'] === 'tahunan') {
				$data['lap_bulan'] = null;
			} else {
				$data['lap_bulan'] = $this->input->post('lap_bulan', TRUE);
			}

			// Ambil data hanya jika form sudah disubmit dan ada tahun yang dipilih
			if (!empty($data['lap_tahun'])) {
				$data['datafilter'] = $this->M_Usia_cerai->usia_cerai($data['lap_bulan'], $data['lap_tahun']);
				$data['stats'] = $this->M_Usia_cerai->get_statistics($data['lap_bulan'], $data['lap_tahun']);
				$data['usia_ranges'] = $this->M_Usia_cerai->get_usia_ranges($data['lap_bulan'], $data['lap_tahun']);
			} else {
				$data['datafilter'] = [];
			}
		} else {
			// Set data kosong jika belum submit
			$data['jenis_laporan'] = 'bulanan'; // Tetap set default jenis laporan
			$data['datafilter'] = [];
		}

		// Define month names for display
		$data['nama_bulan'] = [
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
		];

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_usia_cerai', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export data to Excel
	 * 
	 * @param string $lap_bulan Month (optional, use 'all' for all months)
	 * @param string $lap_tahun Year
	 * @return void
	 */
	public function export_excel($lap_bulan = null, $lap_tahun = null)
	{
		// Sanitize URL parameters
		$lap_bulan = urldecode(trim($lap_bulan));
		$lap_tahun = urldecode(trim($lap_tahun));

		// If parameters not provided via URL, try to get from session or POST
		if (empty($lap_bulan)) {
			$lap_bulan = $this->input->post('lap_bulan');
			if (empty($lap_bulan) && $this->session->userdata('lap_bulan')) {
				$lap_bulan = $this->session->userdata('lap_bulan');
			}
		}

		if (empty($lap_tahun)) {
			$lap_tahun = $this->input->post('lap_tahun');
			if (empty($lap_tahun) && $this->session->userdata('lap_tahun')) {
				$lap_tahun = $this->session->userdata('lap_tahun');
			}
		}

		// Default to current year if still empty
		if (empty($lap_tahun)) {
			$lap_tahun = date('Y');
		}

		// If lap_bulan is 'all', set to null to get all months
		if ($lap_bulan === 'all') {
			$lap_bulan = null;
		}

		// Define month names for display
		$nama_bulan = [
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
		];

		// Get data from model
		$data = $this->M_Usia_cerai->usia_cerai($lap_bulan, $lap_tahun);

		// Set filename
		$month_label = isset($lap_bulan) && isset($nama_bulan[$lap_bulan]) ? $nama_bulan[$lap_bulan] : 'Semua_Bulan';
		$filename = "Data_Usia_Perceraian_{$month_label}_{$lap_tahun}_" . date('Ymd_His') . ".xls";

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
				.highlight {
					background-color: #FFFF99;
				}
				.group-header {
					background-color: #E0E0E0;
					font-weight: bold;
				}
			</style>
		</head>
		<body>
			<h3>Laporan Usia Perceraian " . ($lap_bulan ? $nama_bulan[$lap_bulan] . ' ' : '') . "$lap_tahun</h3>
			<p>Tanggal Export: " . date('d-m-Y H:i:s') . "</p>
			
			<table border='1'>
				<thead>
					<tr>
						<th class='txt-center'>No</th>
						<th>Nomor Perkara</th>
						<th>Tanggal Daftar</th>
						<th>Tanggal Putus</th>
						<th>Penggugat/Pemohon</th>
						<th>Usia P</th>
						<th>Tergugat/Termohon</th>
						<th>Usia T</th>
						<th>Tanggal Nikah</th>
						<th>Lama Pernikahan</th>
						<th>Jenis Cerai</th>
						<th>Faktor Perceraian</th>
					</tr>
				</thead>
				<tbody>";

		$no = 1;
		foreach ($data as $row) {
			echo "<tr>
					<td class='txt-center'>$no</td>
					<td>{$row->nomor_perkara}</td>
					<td>" . date('d-m-Y', strtotime($row->tanggal_pendaftaran)) . "</td>
					<td>" . (!empty($row->tanggal_putusan) ? date('d-m-Y', strtotime($row->tanggal_putusan)) : '-') . "</td>
					<td>{$row->nama_p}</td>
					<td class='txt-center'>" . (!empty($row->usia_p) ? $row->usia_p . ' tahun' : '-') . "</td>
					<td>{$row->nama_t}</td>
					<td class='txt-center'>" . (!empty($row->usia_t) ? $row->usia_t . ' tahun' : '-') . "</td>
					<td>" . (!empty($row->tgl_nikah) ? date('d-m-Y', strtotime($row->tgl_nikah)) : '-') . "</td>
					<td class='txt-center'>" . (!empty($row->lama_nikah) ? $row->lama_nikah . ' tahun' : '-') . "</td>
					<td>{$row->jenis_cerai}</td>
					<td>" . (!empty($row->alasan) ? $row->alasan : '-') . "</td>
				</tr>";
			$no++;
		}

		echo "
				</tbody>
			</table>
			
			<p><strong>Total data:</strong> " . count($data) . "</p>
			
			<h3>Statistik Usia Perceraian</h3>";

		// Get additional statistics
		$stats = $this->M_Usia_cerai->get_statistics($lap_bulan, $lap_tahun);
		$usia_ranges = $this->M_Usia_cerai->get_usia_ranges($lap_bulan, $lap_tahun);

		echo "
			<table border='1'>
				<tr class='group-header'>
					<td colspan='2'>Statistik Dasar</td>
				</tr>
				<tr>
					<td>Total Perceraian</td>
					<td>" . count($data) . "</td>
				</tr>
				<tr>
					<td>Rata-rata Usia Penggugat</td>
					<td>" . (isset($stats->avg_usia_p) ? round($stats->avg_usia_p, 1) . ' tahun' : '-') . "</td>
				</tr>
				<tr>
					<td>Rata-rata Usia Tergugat</td>
					<td>" . (isset($stats->avg_usia_t) ? round($stats->avg_usia_t, 1) . ' tahun' : '-') . "</td>
				</tr>
				<tr>
					<td>Rata-rata Lama Pernikahan</td>
					<td>" . (isset($stats->avg_lama_nikah) ? round($stats->avg_lama_nikah, 1) . ' tahun' : '-') . "</td>
				</tr>
				<tr>
					<td>Total Cerai Talak</td>
					<td>" . (isset($stats->total_cerai_talak) ? $stats->total_cerai_talak : '0') . "</td>
				</tr>
				<tr>
					<td>Total Cerai Gugat</td>
					<td>" . (isset($stats->total_cerai_gugat) ? $stats->total_cerai_gugat : '0') . "</td>
				</tr>
				
				<tr class='group-header'>
					<td colspan='2'>Distribusi Usia</td>
				</tr>
				<tr>
					<td colspan='2'>Penggugat/Pemohon</td>
				</tr>
				<tr>
					<td>Di bawah 20 tahun</td>
					<td>" . (isset($usia_ranges->p_usia_dibawah_20) ? $usia_ranges->p_usia_dibawah_20 : '0') . "</td>
				</tr>
				<tr>
					<td>20-30 tahun</td>
					<td>" . (isset($usia_ranges->p_usia_20_30) ? $usia_ranges->p_usia_20_30 : '0') . "</td>
				</tr>
				<tr>
					<td>31-40 tahun</td>
					<td>" . (isset($usia_ranges->p_usia_31_40) ? $usia_ranges->p_usia_31_40 : '0') . "</td>
				</tr>
				<tr>
					<td>41-50 tahun</td>
					<td>" . (isset($usia_ranges->p_usia_41_50) ? $usia_ranges->p_usia_41_50 : '0') . "</td>
				</tr>
				<tr>
					<td>Di atas 50 tahun</td>
					<td>" . (isset($usia_ranges->p_usia_diatas_50) ? $usia_ranges->p_usia_diatas_50 : '0') . "</td>
				</tr>
				
				<tr>
					<td colspan='2'>Tergugat/Termohon</td>
				</tr>
				<tr>
					<td>Di bawah 20 tahun</td>
					<td>" . (isset($usia_ranges->t_usia_dibawah_20) ? $usia_ranges->t_usia_dibawah_20 : '0') . "</td>
				</tr>
				<tr>
					<td>20-30 tahun</td>
					<td>" . (isset($usia_ranges->t_usia_20_30) ? $usia_ranges->t_usia_20_30 : '0') . "</td>
				</tr>
				<tr>
					<td>31-40 tahun</td>
					<td>" . (isset($usia_ranges->t_usia_31_40) ? $usia_ranges->t_usia_31_40 : '0') . "</td>
				</tr>
				<tr>
					<td>41-50 tahun</td>
					<td>" . (isset($usia_ranges->t_usia_41_50) ? $usia_ranges->t_usia_41_50 : '0') . "</td>
				</tr>
				<tr>
					<td>Di atas 50 tahun</td>
					<td>" . (isset($usia_ranges->t_usia_diatas_50) ? $usia_ranges->t_usia_diatas_50 : '0') . "</td>
				</tr>
				
				<tr class='group-header'>
					<td colspan='2'>Durasi Pernikahan</td>
				</tr>
				<tr>
					<td>Kurang dari 1 tahun</td>
					<td>" . (isset($usia_ranges->nikah_kurang_1_tahun) ? $usia_ranges->nikah_kurang_1_tahun : '0') . "</td>
				</tr>
				<tr>
					<td>1-5 tahun</td>
					<td>" . (isset($usia_ranges->nikah_1_5_tahun) ? $usia_ranges->nikah_1_5_tahun : '0') . "</td>
				</tr>
				<tr>
					<td>6-10 tahun</td>
					<td>" . (isset($usia_ranges->nikah_6_10_tahun) ? $usia_ranges->nikah_6_10_tahun : '0') . "</td>
				</tr>
				<tr>
					<td>Lebih dari 10 tahun</td>
					<td>" . (isset($usia_ranges->nikah_lebih_10_tahun) ? $usia_ranges->nikah_lebih_10_tahun : '0') . "</td>
				</tr>
			</table>
		</body>
		</html>";
		exit;
	}
}
