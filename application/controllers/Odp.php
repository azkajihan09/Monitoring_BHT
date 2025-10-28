<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Odp extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_odp");
	}

	public function index()
	{
		$data = [];

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

		// Get filters from form submission
		$lap_bulan = $this->input->post('lap_bulan', TRUE);
		$lap_tahun = $this->input->post('lap_tahun', TRUE);
		$jenis_filter = $this->input->post('jenis_filter', TRUE);

		// Default to current year if not provided
		if (empty($lap_tahun)) {
			$lap_tahun = date('Y');
		}

		// Default to yearly report if not specified
		if (empty($jenis_filter)) {
			$jenis_filter = 'tahunan';
		}

		// Get month value only for monthly report
		if ($jenis_filter === 'bulanan' && !empty($lap_bulan)) {
			$data['datafilter'] = $this->M_odp->odp($lap_bulan, $lap_tahun);
			$data['stats'] = $this->M_odp->get_odp_stats($lap_bulan, $lap_tahun);
			$data['perkara_distribution'] = $this->M_odp->get_perkara_distribution($lap_bulan, $lap_tahun);
		} else {
			// For yearly report, don't pass month parameter
			$data['datafilter'] = $this->M_odp->odp(null, $lap_tahun);
			$data['stats'] = $this->M_odp->get_odp_stats(null, $lap_tahun);
			$data['perkara_distribution'] = $this->M_odp->get_perkara_distribution(null, $lap_tahun);
			$data['monthly_performance'] = $this->M_odp->get_monthly_performance($lap_tahun);
			$lap_bulan = null; // Ensure it's null for yearly report
		}

		// Add filter parameters to view data
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;
		$data['jenis_filter'] = $jenis_filter;

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_odp', $data);
		$this->load->view('template/new_footer');
	}

	public function detail($perkara_id = NULL)
	{
		if (!$perkara_id) {
			show_404();
			return;
		}

		// Load model to get detailed data
		$data['perkara'] = $this->M_odp->get_detail($perkara_id);

		if (!$data['perkara']) {
			show_404();
			return;
		}

		// Get related documents if available
		$data['dokumen'] = $this->M_odp->get_documents($perkara_id);

		// Get hearing schedule if available
		$data['jadwal_sidang'] = $this->M_odp->get_hearings($perkara_id);

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_lihat_detail', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export data to Excel
	 * 
	 * @param string $lap_bulan Month (optional, 'all' for all months)
	 * @param string $lap_tahun Year
	 * @return void
	 */
	public function export_excel($lap_bulan = 'all', $lap_tahun = null)
	{
		// Check if year is provided
		if (!$lap_tahun) {
			$lap_tahun = date('Y');
		}

		// If month is 'all', set to null for the model
		$bulan = ($lap_bulan === 'all') ? null : $lap_bulan;

		// Load helper
		$this->load->helper('download');

		// Get data from model
		$data = $this->M_odp->get_odp_data($bulan, $lap_tahun);

		// Define month names
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

		// Set filename
		$month_label = isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : 'Semua_Bulan';
		$filename = "Data_ODP_{$month_label}_{$lap_tahun}_" . date('Ymd_His') . ".xls";

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
				}
				.txt-center {
					text-align: center;
				}
				h3 {
					text-align: center;
				}
			</style>
		</head>
		<body>
			<h3>Data One Day Publish (ODP) " . ($bulan ? $nama_bulan[$bulan] . ' ' : '') . "$lap_tahun</h3>
			<p>Tanggal Export: " . date('d-m-Y H:i:s') . "</p>
			
			<table border='1'>
				<thead>
					<tr>
						<th>No</th>
						<th>Nomor Perkara</th>
						<th>Jenis Perkara</th>
						<th>Tanggal Putus</th>
						<th>Tanggal Minutasi</th>
						<th>Tanggal Publish</th>
						<th>Selisih Hari</th>
						<th>Status ODP</th>
					</tr>
				</thead>
				<tbody>";

		$no = 1;
		foreach ($data as $row) {
			echo "<tr>
					<td class='txt-center'>$no</td>
					<td>{$row->nomor_perkara}</td>
					<td>{$row->jenis_perkara_nama}</td>
					<td>" . date('d-m-Y', strtotime($row->tanggal_putusan)) . "</td>
					<td>" . (!empty($row->tanggal_minutasi) ? date('d-m-Y', strtotime($row->tanggal_minutasi)) : '-') . "</td>
					<td>" . date('d-m-Y', strtotime($row->tanggal_publish)) . "</td>
					<td class='txt-center'>{$row->selisih_hari}</td>
					<td>{$row->is_odp}</td>
				</tr>";
			$no++;
		}

		echo "
				</tbody>
			</table>
		</body>
		</html>";
		exit;
	}
}
