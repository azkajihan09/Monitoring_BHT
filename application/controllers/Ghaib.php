<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Ghaib extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_Ghaib");
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

		if ($this->input->post('btn')) {
			$jenis_perkara = $this->input->post('jenis_perkara', TRUE);
			$lap_bulan = $this->input->post('lap_bulan', TRUE);
			$lap_tahun = $this->input->post('lap_tahun', TRUE);

			if (!empty($jenis_perkara) && !empty($lap_bulan) && !empty($lap_tahun)) {
				$data['datafilter'] = $this->M_Ghaib->ghaib($jenis_perkara, $lap_bulan, $lap_tahun);
				$data['stats'] = $this->M_Ghaib->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun);
			} else {
				$data['datafilter'] = [];
			}
		} else {
			$data['datafilter'] = [];
		}

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_ghaib', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export ghaib cases to Excel format
	 * 
	 * @param string $jenis_perkara Case type (e.g., Pdt.G, Pdt.P)
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return void
	 */
	public function export_excel($jenis_perkara = null, $lap_bulan = null, $lap_tahun = null)
	{
		// If parameters not provided via URL, get from POST
		if (empty($jenis_perkara) || empty($lap_bulan) || empty($lap_tahun)) {
			$jenis_perkara = $this->input->post('jenis_perkara', TRUE);
			$lap_bulan = $this->input->post('lap_bulan', TRUE);
			$lap_tahun = $this->input->post('lap_tahun', TRUE);
		}

		// Default to current month/year if still empty
		if (empty($lap_bulan)) {
			$lap_bulan = date('m');
		}

		if (empty($lap_tahun)) {
			$lap_tahun = date('Y');
		}

		if (empty($jenis_perkara)) {
			$jenis_perkara = 'Pdt.G';
		}

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

		// Get data from model
		$data = $this->M_Ghaib->ghaib($jenis_perkara, $lap_bulan, $lap_tahun);
		$stats = $this->M_Ghaib->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun);

		// Set filename
		$filename = "Data_Perkara_Ghaib_{$jenis_perkara}_{$months[$lap_bulan]}_{$lap_tahun}_" . date('Ymd_His') . ".xls";

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
                h3, h4 {
                    text-align: center;
                    margin-bottom: 10px;
                }
                .highlight {
                    background-color: #FFFF99;
                }
                .header-section {
                    background-color: #E0E0E0;
                    font-weight: bold;
                    text-align: left;
                    padding: 5px;
                }
                .stats-table {
                    width: 50%;
                    margin: 20px 0;
                }
            </style>
        </head>
        <body>
            <h3>Data Perkara Ghaib {$jenis_perkara}</h3>
            <h4>Periode: {$months[$lap_bulan]} {$lap_tahun}</h4>
            <p>Tanggal Export: " . date('d-m-Y H:i:s') . "</p>
            
            <div class='header-section'>Statistik Perkara Ghaib</div>
            <table class='stats-table'>
                <tr>
                    <td>Total Perkara Ghaib</td>
                    <td>" . count($data) . "</td>
                </tr>
                <tr>
                    <td>Sudah Diputus</td>
                    <td>" . (isset($stats->putus_count) ? $stats->putus_count : '0') . "</td>
                </tr>
                <tr>
                    <td>Dalam Proses</td>
                    <td>" . (isset($stats->in_process) ? $stats->in_process : '0') . "</td>
                </tr>
                <tr>
                    <td>Rata-rata Durasi (hari)</td>
                    <td>" . (isset($stats->avg_days) ? round($stats->avg_days) : '0') . "</td>
                </tr>
                <tr>
                    <td>Persentase dari Total Perkara</td>
                    <td>" . (isset($stats->percent_of_total) ? round($stats->percent_of_total, 1) . '%' : '0%') . "</td>
                </tr>
            </table>
            
            <div class='header-section'>Detail Perkara</div>
            <table border='1'>
                <thead>
                    <tr>
                        <th class='txt-center'>No</th>
                        <th>Nomor Perkara</th>
                        <th>Majelis Hakim</th>
                        <th>Panitera Pengganti</th>
                        <th>Tgl Pendaftaran</th>
                        <th>Tgl PMH</th>
                        <th>Tgl PHS</th>
                        <th>Sidang Pertama</th>
                        <th>Tgl Putusan</th>
                        <th>Jenis Putusan</th>
                        <th>Keterangan Ghaib</th>
                        <th>Durasi (hari)</th>
                    </tr>
                </thead>
                <tbody>";

		$no = 1;
		foreach ($data as $row) {
			// Calculate days between registration and verdict
			$duration = '';
			if (!empty($row->tanggal_putusan) && !empty($row->tanggal_pendaftaran)) {
				$days = floor((strtotime($row->tanggal_putusan) - strtotime($row->tanggal_pendaftaran)) / (60 * 60 * 24));
				$duration = $days . ' hari';
			} else {
				$duration = 'Dalam proses';
			}

			echo "<tr>
                    <td class='txt-center'>{$no}</td>
                    <td>{$row->nomor_perkara}</td>
                    <td>{$row->majelis_hakim_nama}</td>
                    <td>{$row->panitera_pengganti_text}</td>
                    <td>" . (!empty($row->tanggal_pendaftaran) ? date('d-m-Y', strtotime($row->tanggal_pendaftaran)) : '-') . "</td>
                    <td>" . (!empty($row->penetapan_majelis_hakim) ? date('d-m-Y', strtotime($row->penetapan_majelis_hakim)) : '-') . "</td>
                    <td>" . (!empty($row->penetapan_hari_sidang) ? date('d-m-Y', strtotime($row->penetapan_hari_sidang)) : '-') . "</td>
                    <td>" . (!empty($row->sidang_pertama) ? date('d-m-Y', strtotime($row->sidang_pertama)) : '-') . "</td>
                    <td>" . (!empty($row->tanggal_putusan) ? date('d-m-Y', strtotime($row->tanggal_putusan)) : 'Dalam proses') . "</td>
                    <td>" . (!empty($row->status_putusan_nama) ? $row->status_putusan_nama : '-') . "</td>
                    <td>{$row->alamat_t}</td>
                    <td>{$duration}</td>
                </tr>";
			$no++;
		}

		echo "
                </tbody>
            </table>
            
            <p><strong>Keterangan:</strong></p>
            <ul>
                <li>PMH: Penetapan Majelis Hakim</li>
                <li>PHS: Penetapan Hari Sidang</li>
                <li>Durasi: Jumlah hari dari pendaftaran hingga putusan</li>
            </ul>
            
            <p><strong>Catatan:</strong> Laporan ini memuat data perkara dengan status alamat 'tidak diketahui' atau status ghaib.</p>
        </body>
        </html>";
		exit;
	}
}
