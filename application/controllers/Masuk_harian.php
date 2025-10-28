<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Masuk_harian extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_Masuk_harian");
		$this->load->helper('download');
	}

	public function index()
	{
		// Set default values to prevent undefined variable errors
		$data['datafilter'] = [];
		$data['daily_data'] = [];

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
			$data['datafilter'] = $this->M_Masuk_harian->masuk_harian($jenis_perkara, $lap_bulan, $lap_tahun, $search);
			$data['daily_data'] = $this->M_Masuk_harian->masuk_per_hari($jenis_perkara, $lap_bulan, $lap_tahun, $search);

			// Get statistics
			$data['stats'] = $this->M_Masuk_harian->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		}

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_masuk_harian', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Show detailed cases for specific panel and day
	 * 
	 * @param string $majelis_id Panel ID
	 * @param string $hari Day of week (1=Monday, 5=Friday)
	 */
	public function detail($majelis_id = null, $hari = null)
	{
		if (!$majelis_id) {
			show_404();
			return;
		}

		// Convert dashes to commas if needed
		$majelis_id = str_replace('-', ',', $majelis_id);

		// Get filter parameters
		$jenis_perkara = $this->input->get('jenis_perkara');
		$lap_bulan = $this->input->get('lap_bulan');
		$lap_tahun = $this->input->get('lap_tahun');

		// Get detailed cases
		$data['cases'] = $this->M_Masuk_harian->get_detail_cases($majelis_id, $hari, $jenis_perkara, $lap_bulan, $lap_tahun);
		$data['majelis_id'] = $majelis_id;
		$data['hari'] = $hari;

		// Get panel info
		if (!empty($data['cases'])) {
			$data['majelis_hakim_nama'] = $data['cases'][0]->majelis_hakim_nama;
		} else {
			$data['majelis_hakim_nama'] = 'Unknown Panel';
		}

		// Day names
		$days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
		$data['hari_nama'] = isset($days[$hari]) ? $days[$hari] : 'Semua Hari';

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_masuk_harian_detail', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export data to Excel
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
		$datafilter = $this->M_Masuk_harian->masuk_harian($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$daily_data = $this->M_Masuk_harian->masuk_per_hari($jenis_perkara, $lap_bulan, $lap_tahun, $search);
		$stats = $this->M_Masuk_harian->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun, $search);

		// Define month names
		$months = [
			'01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
			'05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
			'09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
		];

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()
			->setCreator("SIPP")
			->setLastModifiedBy("SIPP")
			->setTitle("Laporan Perkara Masuk Harian")
			->setSubject("Laporan Perkara Masuk Harian")
			->setDescription("Laporan Perkara Masuk Per Majelis Per Hari")
			->setKeywords("perkara masuk harian")
			->setCategory("Laporan");

		// Set active sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Add header row
		$objPHPExcel->getActiveSheet()
			->setCellValue('A1', 'LAPORAN PERKARA MASUK HARIAN PER MAJELIS')
			->setCellValue('A2', 'Periode: ' . $months[$lap_bulan] . ' ' . $lap_tahun)
			->setCellValue('A3', 'Jenis Perkara: ' . ($jenis_perkara == 'all' ? 'Semua Jenis' : $jenis_perkara))
			->setCellValue('A5', 'No')
			->setCellValue('B5', 'Majelis Hakim')
			->setCellValue('C5', 'Senin')
			->setCellValue('D5', 'Selasa')
			->setCellValue('E5', 'Rabu')
			->setCellValue('F5', 'Kamis')
			->setCellValue('G5', 'Jumat')
			->setCellValue('H5', 'Total');

		// Style header
		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true)->setSize(14);
		$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A5:H5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C0C0C0');

		// Merge cells for title
		$objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
		$objPHPExcel->getActiveSheet()->mergeCells('A2:H2');
		$objPHPExcel->getActiveSheet()->mergeCells('A3:H3');

		// Add data rows
		$row = 6;
		$no = 1;
		foreach ($datafilter as $data) {
			$objPHPExcel->getActiveSheet()
				->setCellValue('A' . $row, $no++)
				->setCellValue('B' . $row, strip_tags(str_replace('<br />', ' | ', $data->majelis_hakim_nama)))
				->setCellValue('C' . $row, $data->senin)
				->setCellValue('D' . $row, $data->selasa)
				->setCellValue('E' . $row, $data->rabu)
				->setCellValue('F' . $row, $data->kamis)
				->setCellValue('G' . $row, $data->jumat)
				->setCellValue('H' . $row, $data->total_masuk);
			$row++;
		}

		// Set column widths
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);

		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Perkara Masuk Harian');

		// Set filename
		$filename = "Perkara_Masuk_Harian_" . $months[$lap_bulan] . "_" . $lap_tahun . ".xlsx";

		// Output headers
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	}
}