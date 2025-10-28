<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Itsbat extends CI_Controller
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Set timezone
		date_default_timezone_set('Asia/Jakarta');

		// Load necessary models and libraries
		$this->load->model("M_Itsbat");
		$this->load->library('form_validation');
		$this->load->helper(['date', 'itsbat']);
	}

	/**
	 * Index page for Itsbat Nikah
	 */
	public function index()
	{
		// Set default values
		$data = array(
			'title' => 'Laporan Itsbat Nikah',
			'datafilter' => array(),
			'statistics' => null
		);

		// Process form submission
		if ($this->input->method() === 'post') {
			// Set validation rules
			$this->form_validation->set_rules('lap_bulan', 'Bulan Laporan', 'required|trim|in_list[01,02,03,04,05,06,07,08,09,10,11,12]');
			$this->form_validation->set_rules('lap_tahun', 'Tahun Laporan', 'required|trim|numeric|min_length[4]|max_length[4]');

			if ($this->form_validation->run() === FALSE) {
				// Form validation failed
				$data['error'] = validation_errors();
			} else {
				// Get form inputs
				$lap_bulan = $this->input->post('lap_bulan', TRUE);
				$lap_tahun = $this->input->post('lap_tahun', TRUE);

				// Get data from model
				$data['datafilter'] = $this->M_Itsbat->itsbat($lap_bulan, $lap_tahun);
				$data['statistics'] = $this->M_Itsbat->getItsbatStatistics($lap_bulan, $lap_tahun);
			}
		}

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_itsbat', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export data to Excel
	 */
	public function export()
	{
		// Input validation
		$lap_bulan = $this->input->get('lap_bulan', TRUE);
		$lap_tahun = $this->input->get('lap_tahun', TRUE);

		if (empty($lap_bulan) || empty($lap_tahun)) {
			show_error('Parameter bulan dan tahun diperlukan.');
			return;
		}

		// Get data
		$data = $this->M_Itsbat->itsbat($lap_bulan, $lap_tahun);

		// Load PhpSpreadsheet library (you need to install this)
		$this->load->library('PHPExcel');

		// Create a simple report
		// Implementation depends on your spreadsheet library
	}

	/**
	 * View detailed statistics for Itsbat Nikah cases
	 */
	public function statistics()
	{
		// Implementation for statistics page
	}
}
