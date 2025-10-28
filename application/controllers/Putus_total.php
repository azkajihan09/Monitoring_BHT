<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Putus_total extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_Putus_total");
		$this->load->helper('download');
	}

	public function index()
	{
		// Set default values to prevent undefined variable errors
		$data['datafilter'] = [];

		// Get filter parameters from form
		$jenis_perkara = $this->input->post('jenis_perkara');
		$lap_bulan = $this->input->post('lap_bulan');
		$lap_tahun = $this->input->post('lap_tahun');
		$search = $this->input->post('search');

		// Check if form was submitted (or set defaults if it's the first page load)
		if ($this->input->post('btn') || ($this->input->server('REQUEST_METHOD') === 'GET' && $this->uri->segment(2) == '')) {
			// If no values provided, set current month and year as default
			if (empty($jenis_perkara)) $jenis_perkara = 'Pdt.G';
			if (empty($lap_bulan)) $lap_bulan = date('m');
			if (empty($lap_tahun)) $lap_tahun = date('Y');

			// Get data from model
			$data['datafilter'] = $this->M_Putus_total->putus_total($jenis_perkara, $lap_bulan, $lap_tahun, $search);

			// Get statistics
			$data['stats'] = $this->M_Putus_total->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		}

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_putus_total', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Show detailed cases for specific panel
	 * 
	 * @param string $majelis_id Panel ID (should use dash instead of commas for multiple IDs)
	 */
	public function detail($majelis_id = null)
	{
		if (!$majelis_id) {
			show_404();
			return;
		}

		// Convert dashes to commas if needed (safer than having commas in URL)
		$majelis_id = str_replace('-', ',', $majelis_id);

		// Get filter parameters
		$jenis_perkara = $this->input->get('jenis_perkara');
		$lap_bulan = $this->input->get('lap_bulan');
		$lap_tahun = $this->input->get('lap_tahun');

		// Get detailed cases
		$data['cases'] = $this->M_Putus_total->get_detail_cases($majelis_id, $jenis_perkara, $lap_bulan, $lap_tahun);
		$data['majelis_id'] = $majelis_id; // Store for export links

		// Get panel info (first case is enough to get the panel name)
		if (!empty($data['cases'])) {
			$data['majelis_hakim_nama'] = $data['cases'][0]->majelis_hakim_nama;
		} else {
			$data['majelis_hakim_nama'] = 'Unknown Panel';
		}

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_putus_total_detail', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export data to Excel
	 * 
	 * @param string $jenis_perkara Case type
	 * @param string $lap_bulan Month
	 * @param string $lap_tahun Year
	 * @param string $search Search query (optional, URL encoded)
	 */
	public function export_excel($jenis_perkara = 'Pdt.G', $lap_bulan = null, $lap_tahun = null, $search = null)
	{
		// Set defaults if not provided
		if (empty($lap_bulan)) $lap_bulan = date('m');
		if (empty($lap_tahun)) $lap_tahun = date('Y');
		if (!empty($search)) $search = urldecode($search);

		// Load required library
		$this->load->library('phpexcel');

		// Get data
		$datafilter = $this->M_Putus_total->putus_total($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$stats = $this->M_Putus_total->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun, $search);

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

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()
			->setCreator("SIPP")
			->setLastModifiedBy("SIPP")
			->setTitle("Laporan Perkara Putus Total")
			->setSubject("Laporan Perkara Putus Total")
			->setDescription("Laporan Perkara Putus Total Per Majelis")
			->setKeywords("perkara putus total")
			->setCategory("Laporan");

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Add header row
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', 'LAPORAN PERKARA PUTUS TOTAL PER MAJELIS')
			->setCellValue('A2', 'Periode: ' . $months[$lap_bulan] . ' ' . $lap_tahun)
			->setCellValue('A3', 'Jenis Perkara: ' . ($jenis_perkara == 'all' ? 'Semua Jenis' : $jenis_perkara))
			->setCellValue('A5', 'No')
			->setCellValue('B5', 'Majelis Hakim')
			->setCellValue('C5', 'Jumlah Perkara')
			->setCellValue('D5', 'Persentase');

		// Style header
		$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A5:D5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C0C0C0');

		// Merge cells for title
		$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:D2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:D3');

		// Add data rows
		$row = 6;
		$no = 1;
		foreach ($datafilter as $data) {
			$objPHPExcel->getActiveSheet()
				->setCellValue('A' . $row, $no++)
				->setCellValue('B' . $row, strip_tags(str_replace('<br />', ' | ', $data->majelis_hakim_nama)))
				->setCellValue('C' . $row, $data->putus)
				->setCellValue('D' . $row, round(($data->putus / $stats->total_perkara) * 100, 2) . '%');
			$row++;
		}

		// Add statistics
		$row += 2;
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'STATISTIK DISTRIBUSI')
			->getStyle('A' . $row)->getFont()->setBold(true);

		$row++;
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'Total Perkara Putus')
			->setCellValue('B' . $row, $stats->total_perkara);

		$row++;
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'Total Majelis')
			->setCellValue('B' . $row, $stats->total_majelis);

		$row++;
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'Rata-rata Beban per Majelis')
			->setCellValue('B' . $row, round($stats->avg_per_majelis, 2));

		$row++;
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'Beban Terbanyak')
			->setCellValue('B' . $row, $stats->max_load . ' (' . $stats->max_load_majelis . ')');

		$row++;
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'Beban Tersedikit')
			->setCellValue('B' . $row, $stats->min_load . ' (' . $stats->min_load_majelis . ')');

		// Set column widths
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Perkara Putus Total');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Set filename
		$filename = "Perkara_Putus_Total_" . $months[$lap_bulan] . "_" . $lap_tahun . ".xlsx";

		// Redirect output to a client's web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		// If using IE
		header('Cache-Control: max-age=1');

		// If using IE over HTTPS
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // Always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}

	/**
	 * Export detailed cases for specific panel to Excel
	 * 
	 * @param string $majelis_id Panel ID
	 */
	public function export_detail($majelis_id = null)
	{
		if (!$majelis_id) {
			show_404();
			return;
		}

		// Get parameters from URL
		$jenis_perkara = $this->input->get('jenis_perkara');
		$lap_bulan = $this->input->get('lap_bulan');
		$lap_tahun = $this->input->get('lap_tahun');
		$status = $this->input->get('status');

		// Get detailed cases
		$cases = $this->M_Putus_total->get_detail_cases($majelis_id, $jenis_perkara, $lap_bulan, $lap_tahun);

		// Get panel info (first case is enough to get the panel name)
		$majelis_hakim_nama = !empty($cases) ? $cases[0]->majelis_hakim_nama : 'Unknown Panel';

		// Filter by status if requested
		if ($status === 'aktif') {
			$cases = array_filter($cases, function ($case) {
				return empty($case->tanggal_putusan);
			});
		} elseif ($status === 'selesai') {
			$cases = array_filter($cases, function ($case) {
				return !empty($case->tanggal_putusan);
			});
		}

		// Load required library
		$this->load->library('phpexcel');

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()
			->setCreator("SIPP")
			->setLastModifiedBy("SIPP")
			->setTitle("Daftar Perkara Putus Majelis Hakim")
			->setSubject("Daftar Perkara Putus")
			->setDescription("Daftar perkara putus per majelis hakim")
			->setKeywords("perkara putus, majelis hakim")
			->setCategory("Laporan");

		// Set active sheet index
		$objPHPExcel->setActiveSheetIndex(0);

		// Clean up panel name for display
		$cleanMajelisName = str_replace('<br />', ' - ', $majelis_hakim_nama);

		// Add header
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', 'DAFTAR PERKARA PUTUS MAJELIS HAKIM')
			->setCellValue('A2', strip_tags($cleanMajelisName))
			->setCellValue('A4', 'No')
			->setCellValue('B4', 'Nomor Perkara')
			->setCellValue('C4', 'Jenis Perkara')
			->setCellValue('D4', 'Tanggal Daftar')
			->setCellValue('E4', 'Penetapan Majelis')
			->setCellValue('F4', 'Sidang Pertama')
			->setCellValue('G4', 'Tanggal Putusan')
			->setCellValue('H4', 'Status');

		// Style header
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A4:H4')->getFill()
			->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
			->getStartColor()->setRGB('C0C0C0');

		// Merge cells for title
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:H2');

		// Add data rows
		$row = 5;
		$no = 1;
		foreach ($cases as $case) {
			$objPHPExcel->getActiveSheet()
				->setCellValue('A' . $row, $no++)
				->setCellValue('B' . $row, $case->nomor_perkara)
				->setCellValue('C' . $row, $case->jenis_perkara_nama)
				->setCellValue('D' . $row, date('d-m-Y', strtotime($case->tanggal_pendaftaran)))
				->setCellValue('E' . $row, !empty($case->penetapan_majelis_hakim) ? date('d-m-Y', strtotime($case->penetapan_majelis_hakim)) : 'Belum Ditetapkan')
				->setCellValue('F' . $row, !empty($case->sidang_pertama) ? date('d-m-Y', strtotime($case->sidang_pertama)) : 'Belum Dijadwalkan')
				->setCellValue('G' . $row, !empty($case->tanggal_putusan) ? date('d-m-Y', strtotime($case->tanggal_putusan)) : 'Dalam Proses')
				->setCellValue('H' . $row, !empty($case->tanggal_putusan) ? 'Selesai' : 'Aktif');
			$row++;
		}

		// Set column widths
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);

		// Add summary
		$row += 2;
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'Jumlah Perkara: ' . count($cases));
		$row++;
		$active = 0;
		$completed = 0;
		foreach ($cases as $case) {
			if (!empty($case->tanggal_putusan)) {
				$completed++;
			} else {
				$active++;
			}
		}
		$objPHPExcel->getActiveSheet()
			->setCellValue('A' . $row, 'Perkara Aktif: ' . $active . ', Perkara Selesai: ' . $completed);

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Daftar Perkara Putus');

		// Set active sheet index
		$objPHPExcel->setActiveSheetIndex(0);

		// Set filename
		$filename = "Daftar_Perkara_Putus_" . date('YmdHis') . ".xlsx";

		// Send headers
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
}