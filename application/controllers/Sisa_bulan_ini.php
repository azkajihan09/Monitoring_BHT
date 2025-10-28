<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Sisa_bulan_ini extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_sisa_bulan_ini");
		$this->load->library('session');
	}

	public function index()
	{
		$data = [];

		// Define month names
		$data['months'] = [
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

		// Get filter parameters from form or defaults
		$jenis_perkara = $this->input->get_post('jenis_perkara');
		if (empty($jenis_perkara)) {
			$jenis_perkara = 'Pdt.G';
		}
		$lap_bulan = $this->input->get_post('lap_bulan');
		if (empty($lap_bulan)) {
			$lap_bulan = date('m');
		}
		$lap_tahun = $this->input->get_post('lap_tahun');
		if (empty($lap_tahun)) {
			$lap_tahun = date('Y');
		}
		$search = $this->input->get_post('search');

		// Store filters in session for export
		$this->session->set_userdata('jenis_perkara', $jenis_perkara);
		$this->session->set_userdata('lap_bulan', $lap_bulan);
		$this->session->set_userdata('lap_tahun', $lap_tahun);
		$this->session->set_userdata('search', $search);

		// Get data and statistics from model
		$data['datafilter'] = $this->M_sisa_bulan_ini->sisa_bulan_ini($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$data['stats'] = $this->M_sisa_bulan_ini->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun);

		// Pass form inputs to view
		$data['jenis_perkara'] = $jenis_perkara;
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;
		$data['search'] = $search;

		// Calculate total remaining cases
		$data['total_cases'] = 0;
		foreach ($data['datafilter'] as $row) {
			$data['total_cases'] += $row->sisa_bulan_ini;
		}

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_sisa_bulan_ini', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Show detail of cases for specific judge panel(s)
	 */
	public function detail($majelis_id = null)
	{
		if (!$majelis_id) {
			show_404();
			return;
		}

		// Get filters from session or request
		$jenis_perkara = $this->input->get('jenis_perkara');
		if (empty($jenis_perkara)) {
			$jenis_perkara = $this->session->userdata('jenis_perkara');
			if (empty($jenis_perkara)) {
				$jenis_perkara = 'Pdt.G';
			}
		}

		$lap_bulan = $this->input->get('lap_bulan');
		if (empty($lap_bulan)) {
			$lap_bulan = $this->session->userdata('lap_bulan');
			if (empty($lap_bulan)) {
				$lap_bulan = date('m');
			}
		}

		$lap_tahun = $this->input->get('lap_tahun');
		if (empty($lap_tahun)) {
			$lap_tahun = $this->session->userdata('lap_tahun');
			if (empty($lap_tahun)) {
				$lap_tahun = date('Y');
			}
		}

		$search = $this->input->get('search');

		// Define month names
		$data['months'] = [
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

		// Get case details - support multiple majelis IDs separated by commas
		$data['detail_cases'] = $this->M_sisa_bulan_ini->get_detail_cases($majelis_id, $jenis_perkara, $lap_bulan, $lap_tahun, $search);

		// Get panel names from the cases
		$data['majelis_list'] = [];
		$majelis_names = [];
		if (!empty($data['detail_cases'])) {
			foreach ($data['detail_cases'] as $case) {
				if (isset($case->majelis_hakim_nama) && !in_array($case->majelis_hakim_nama, $majelis_names)) {
					$majelis_names[] = $case->majelis_hakim_nama;
					$data['majelis_list'][] = $case->majelis_hakim_nama;
				}
			}
			// If there's only one majelis, store its name directly
			if (count($majelis_names) == 1) {
				$data['majelis_hakim_nama'] = $majelis_names[0];
			}
		} else {
			$data['majelis_hakim_nama'] = 'Majelis Hakim';
		}

		// Pass other data to view
		$data['majelis_hakim_id'] = $majelis_id;
		$data['jenis_perkara'] = $jenis_perkara;
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;
		$data['search'] = $search;

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_sisa_bulan_ini_detail', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export data to Excel
	 */
	public function export_excel()
	{
		// Get filters from session or defaults
		$jenis_perkara = $this->session->userdata('jenis_perkara');
		if (empty($jenis_perkara)) {
			$jenis_perkara = 'Pdt.G';
		}

		$lap_bulan = $this->session->userdata('lap_bulan');
		if (empty($lap_bulan)) {
			$lap_bulan = date('m');
		}

		$lap_tahun = $this->session->userdata('lap_tahun');
		if (empty($lap_tahun)) {
			$lap_tahun = date('Y');
		}

		$search = $this->session->userdata('search');

		// Define month names
		$months = [
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

		// Get data for export
		$data = $this->M_sisa_bulan_ini->sisa_bulan_ini($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$stats = $this->M_sisa_bulan_ini->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun);

		// Calculate total remaining cases
		$total_cases = 0;
		foreach ($data as $row) {
			$total_cases += $row->sisa_bulan_ini;
		}

		// Set headers for Excel download
		$filename = "Laporan_Sisa_Perkara_" . $months[$lap_bulan] . "_" . $lap_tahun . ".xls";
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		// Generate Excel content as HTML
		echo '<html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style>
                    table { border-collapse: collapse; }
                    th, td { border: 1px solid #000; padding: 5px; }
                    th { background-color: #f0f0f0; }
                    h3, h4 { text-align: center; }
                    .highlight { font-weight: bold; background-color: #ffff99; }
                </style>
            </head>
            <body>
                <h3>LAPORAN SISA PERKARA BULAN ' . strtoupper($months[$lap_bulan]) . ' ' . $lap_tahun . '</h3>
                <p>Tanggal Cetak: ' . date('d-m-Y H:i:s') . '</p>';

		// Statistics section
		echo '<h4>RINGKASAN STATISTIK</h4>
            <table>
                <tr>
                    <th colspan="2">Statistik Dasar</th>
                </tr>
                <tr>
                    <td>Total Sisa Perkara</td>
                    <td>' . $total_cases . '</td>
                </tr>
                <tr>
                    <td>Total Majelis</td>
                    <td>' . count($data) . '</td>
                </tr>
                <tr>
                    <td>Rata-rata Umur Perkara</td>
                    <td>' . round($stats->avg_case_age) . ' hari</td>
                </tr>
                <tr>
                    <td>Umur Perkara Tertua</td>
                    <td>' . round($stats->oldest_case) . ' hari</td>
                </tr>
            </table>';

		// Case age distribution
		if (isset($stats->age_distribution)) {
			echo '<h4>DISTRIBUSI UMUR PERKARA</h4>
                <table>
                    <tr>
                        <th>Kategori Umur</th>
                        <th>Jumlah</th>
                    </tr>
                    <tr>
                        <td>Kurang dari 30 hari</td>
                        <td>' . $stats->age_distribution->under_30_days . '</td>
                    </tr>
                    <tr>
                        <td>30 - 90 hari</td>
                        <td>' . $stats->age_distribution->under_3_months . '</td>
                    </tr>
                    <tr>
                        <td>91 - 180 hari</td>
                        <td>' . $stats->age_distribution->under_6_months . '</td>
                    </tr>
                    <tr>
                        <td>Lebih dari 180 hari</td>
                        <td>' . $stats->age_distribution->over_6_months . '</td>
                    </tr>
                </table>';
		}

		// Main data
		echo '<h4>DATA SISA PERKARA PER MAJELIS HAKIM</h4>
            <table>
                <tr>
                    <th>No</th>
                    <th>Majelis Hakim</th>
                    <th>Jumlah Sisa Perkara</th>
                </tr>';

		$no = 1;
		foreach ($data as $row) {
			echo '<tr>
                <td>' . $no++ . '</td>
                <td>' . $row->majelis_hakim_nama . '</td>
                <td>' . $row->sisa_bulan_ini . '</td>
            </tr>';
		}

		echo '</table>';
		echo '</body></html>';
		exit;
	}

	/**
	 * Export detail cases for a specific panel to Excel
	 */
	public function export_detail($majelis_id = null)
	{
		if (!$majelis_id) {
			show_404();
			return;
		}

		// Get filters from session or defaults
		$jenis_perkara = $this->input->get('jenis_perkara');
		if (empty($jenis_perkara)) {
			$jenis_perkara = $this->session->userdata('jenis_perkara');
			if (empty($jenis_perkara)) {
				$jenis_perkara = 'Pdt.G';
			}
		}

		$lap_bulan = $this->input->get('lap_bulan');
		if (empty($lap_bulan)) {
			$lap_bulan = $this->session->userdata('lap_bulan');
			if (empty($lap_bulan)) {
				$lap_bulan = date('m');
			}
		}

		$lap_tahun = $this->input->get('lap_tahun');
		if (empty($lap_tahun)) {
			$lap_tahun = $this->session->userdata('lap_tahun');
			if (empty($lap_tahun)) {
				$lap_tahun = date('Y');
			}
		}

		// Define month names
		$months = [
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

		// Get case details
		$cases = $this->M_sisa_bulan_ini->get_detail_cases($majelis_id, $jenis_perkara, $lap_bulan, $lap_tahun);

		// Get panel name (from first case if available)
		if (!empty($cases)) {
			$majelis_hakim_nama = isset($cases[0]->majelis_hakim_nama) ? $cases[0]->majelis_hakim_nama : 'Majelis Hakim';
		} else {
			$majelis_hakim_nama = 'Majelis Hakim';
		}

		// Set headers for Excel download
		$filename = "Detail_Sisa_Perkara_" . str_replace(' ', '_', strip_tags(str_replace('<br />', ' ', $majelis_hakim_nama))) . "_" . $lap_tahun . ".xls";
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");

		// Generate Excel content as HTML
		echo '<html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style>
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid #000; padding: 5px; }
                    th { background-color: #f0f0f0; }
                    h3, h4 { text-align: center; }
                </style>
            </head>
            <body>
                <h3>DETAIL SISA PERKARA MAJELIS HAKIM</h3>
                <h4>' . strip_tags(str_replace('<br />', ' | ', $majelis_hakim_nama)) . '</h4>
                <p>Periode: ' . $months[$lap_bulan] . ' ' . $lap_tahun . '</p>
                <p>Tanggal Cetak: ' . date('d-m-Y H:i:s') . '</p>
                
                <table>
                    <tr>
                        <th>No</th>
                        <th>Nomor Perkara</th>
                        <th>Jenis Perkara</th>
                        <th>Tgl Daftar</th>
                        <th>Umur Perkara</th>
                        <th>Penggugat/Pemohon</th>
                        <th>Tergugat/Termohon</th>
                        <th>Sidang Terakhir</th>
                        <th>Agenda Terakhir</th>
                    </tr>';

		$no = 1;
		foreach ($cases as $case) {
			echo '<tr>
                <td>' . $no++ . '</td>
                <td>' . $case->nomor_perkara . '</td>
                <td>' . $case->jenis_perkara_nama . '</td>
                <td>' . date('d-m-Y', strtotime($case->tanggal_pendaftaran)) . '</td>
                <td>' . $case->usia_perkara . ' hari</td>
                <td>' . $case->nama_penggugat . '</td>
                <td>' . $case->nama_tergugat . '</td>
                <td>' . (!empty($case->tgl_sidang_terakhir) ? date('d-m-Y', strtotime($case->tgl_sidang_terakhir)) : '-') . '</td>
                <td>' . (!empty($case->agenda_terakhir) ? $case->agenda_terakhir : '-') . '</td>
            </tr>';
		}

		echo '</table>
            <p><strong>Total:</strong> ' . count($cases) . ' perkara</p>
        </body></html>';
		exit;
	}
}
