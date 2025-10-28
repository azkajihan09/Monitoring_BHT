<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Putus extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_putus");
		$this->load->library('pagination');
		$this->load->library('session'); // Load the session library
	}

	public function index()
	{
		$data = [];

		// Define month names for display
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

		// Get search parameters
		$jenis_perkara = $this->input->get_post('jenis_perkara', TRUE);
		$lap_bulan = $this->input->get_post('lap_bulan', TRUE);
		$lap_tahun = $this->input->get_post('lap_tahun', TRUE);
		$search = $this->input->get_post('search', TRUE);
		$page = $this->input->get('page', TRUE) ? $this->input->get('page', TRUE) : 1;

		if (empty($jenis_perkara)) $jenis_perkara = 'all';
		if (empty($lap_tahun)) $lap_tahun = date('Y');

		// Store filter parameters in session
		$this->session->set_userdata('jenis_perkara', $jenis_perkara);
		$this->session->set_userdata('lap_bulan', $lap_bulan);
		$this->session->set_userdata('lap_tahun', $lap_tahun);
		$this->session->set_userdata('search', $search);

		// Pass filters to view
		$data['jenis_perkara'] = $jenis_perkara;
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;
		$data['search'] = $search;

		// Pagination config
		$config['base_url'] = site_url('Putus/index');
		$config['total_rows'] = $this->M_putus->count_putus($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$config['per_page'] = 25;
		$config['page_query_string'] = TRUE;
		$config['query_string_segment'] = 'page';
		$config['use_page_numbers'] = TRUE;

		// Bootstrap 4 pagination style
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['attributes'] = ['class' => 'page-link'];
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';

		// Initialize pagination
		$this->pagination->initialize($config);
		$data['pagination'] = $this->pagination->create_links();

		// Calculate offset
		$offset = ($page - 1) * $config['per_page'];
		$data['offset'] = $offset;

		// Get data
		$data['datafilter'] = $this->M_putus->get_putus($jenis_perkara, $lap_bulan, $lap_tahun, $search, $config['per_page'], $offset);
		$data['total_rows'] = $config['total_rows'];

		// Get statistics
		$data['stats'] = $this->M_putus->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun);

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_putus', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export data to Excel
	 */
	public function export_excel()
	{
		// Get filter parameters from session or request
		$jenis_perkara = $this->input->get('jenis_perkara') ? $this->input->get('jenis_perkara') : $this->session->userdata('jenis_perkara');
		$lap_bulan = $this->input->get('lap_bulan') ? $this->input->get('lap_bulan') : $this->session->userdata('lap_bulan');
		$lap_tahun = $this->input->get('lap_tahun') ? $this->input->get('lap_tahun') : $this->session->userdata('lap_tahun');
		$search = $this->input->get('search') ? $this->input->get('search') : $this->session->userdata('search');

		if (empty($jenis_perkara)) $jenis_perkara = 'all';
		if (empty($lap_tahun)) $lap_tahun = date('Y');

		// Load data without limit for export
		$data = $this->M_putus->get_putus($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$stats = $this->M_putus->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun);

		// Define month names for display
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

		// Build filename
		$period = $lap_bulan ? $months[$lap_bulan] . '_' . $lap_tahun : $lap_tahun;
		$filename = "Laporan_Perkara_Putus_{$period}_" . date('Ymd_His') . ".xls";

		// Set headers for Excel download
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Cache-Control: max-age=0");

		// Generate Excel content using HTML
		echo "
        <html>
        <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
            <style>
                table { border-collapse: collapse; }
                th, td { border: 1px solid #000000; padding: 5px; }
                th { background-color: #D3D3D3; font-weight: bold; }
                h3 { text-align: center; }
            </style>
        </head>
        <body>
            <h3>LAPORAN PERKARA PUTUS " . ($lap_bulan ? strtoupper($months[$lap_bulan]) . ' ' : '') . $lap_tahun . "</h3>
            
            <h4>STATISTIK PERKARA PUTUS</h4>
            <table border='1'>
                <tr>
                    <th>Parameter</th>
                    <th>Nilai</th>
                </tr>
                <tr>
                    <td>Total Perkara Putus</td>
                    <td>" . $stats->total_perkara . "</td>
                </tr>
                <tr>
                    <td>Rata-rata Durasi Perkara</td>
                    <td>" . round($stats->avg_duration) . " hari</td>
                </tr>
                <tr>
                    <td>Durasi Terpendek</td>
                    <td>" . $stats->min_duration . " hari</td>
                </tr>
                <tr>
                    <td>Durasi Terpanjang</td>
                    <td>" . $stats->max_duration . " hari</td>
                </tr>
                <tr>
                    <td>Jumlah Perkara Minutasi</td>
                    <td>" . $stats->total_minutasi . " (" . $stats->minutasi_percentage . "%)</td>
                </tr>
            </table>
            
            <h4>DISTRIBUSI STATUS PUTUSAN</h4>
            <table border='1'>
                <tr>
                    <th>Status Putusan</th>
                    <th>Jumlah</th>
                </tr>";

		foreach ($stats->status_distribution as $status) {
			echo "<tr>
                <td>" . $status->status_putusan_nama . "</td>
                <td>" . $status->total . "</td>
            </tr>";
		}

		echo "</table>
            
            <h4>DAFTAR PERKARA PUTUS</h4>
            <table border='1'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor Perkara</th>
                        <th>Jenis Perkara</th>
                        <th>Tanggal Pendaftaran</th>
                        <th>Tanggal Putusan</th>
                        <th>Durasi (hari)</th>
                        <th>Status Putusan</th>
                        <th>Tanggal Minutasi</th>
                        <th>Majelis Hakim</th>
                        <th>Panitera Pengganti</th>
                    </tr>
                </thead>
                <tbody>";

		$no = 1;
		foreach ($data as $row) {
			// Strip HTML tags from majelis_hakim_nama and panitera_pengganti_text
			$majelis_hakim_nama = strip_tags(str_replace('<br />', ' | ', $row->majelis_hakim_nama));
			$panitera_pengganti_text = strip_tags(str_replace('<br />', ' | ', $row->panitera_pengganti_text));

			echo "<tr>
                <td>" . $no++ . "</td>
                <td>" . $row->nomor_perkara . "</td>
                <td>" . $row->jenis_perkara_nama . "</td>
                <td>" . date('d-m-Y', strtotime($row->tanggal_pendaftaran)) . "</td>
                <td>" . date('d-m-Y', strtotime($row->tanggal_putusan)) . "</td>
                <td>" . $row->durasi_perkara . "</td>
                <td>" . $row->status_putusan_nama . "</td>
                <td>" . ($row->tanggal_minutasi ? date('d-m-Y', strtotime($row->tanggal_minutasi)) : '-') . "</td>
                <td>" . $majelis_hakim_nama . "</td>
                <td>" . $panitera_pengganti_text . "</td>
            </tr>";
		}

		echo "</tbody>
            </table>
            
            <p><strong>Jumlah data:</strong> " . count($data) . "</p>
            <p><strong>Tanggal cetak:</strong> " . date('d-m-Y H:i:s') . "</p>
        </body>
        </html>";
		exit;
	}
}
