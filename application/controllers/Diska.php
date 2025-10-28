<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Diska extends CI_Controller
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
		$this->load->model("M_Diska");
		$this->load->library('form_validation');
		$this->load->helper(['date', 'url', 'html']);
	}

	/**
	 * Index page for Dispensasi Kawin
	 */
	public function index()
	{
		// Set default values
		$data = [
			'title' => 'Laporan Dispensasi Kawin',
			'datafilter' => [],
			'statistics' => null,
		];

		// Current month and year as default
		$data['current_month'] = date('m');
		$data['current_year'] = date('Y');

		// Process form submission
		if ($this->input->method() === 'post') {
			// Get report type
			$jenis_laporan = $this->input->post('jenis_laporan', TRUE);

			// Set validation rules
			if ($jenis_laporan === 'tahunan') {
				$this->form_validation->set_rules('lap_tahun', 'Tahun Laporan', 'required|trim|numeric|min_length[4]|max_length[4]');
			} else {
				$this->form_validation->set_rules('lap_bulan', 'Bulan Laporan', 'required|trim|in_list[01,02,03,04,05,06,07,08,09,10,11,12]');
				$this->form_validation->set_rules('lap_tahun', 'Tahun Laporan', 'required|trim|numeric|min_length[4]|max_length[4]');
			}

			if ($this->form_validation->run() === FALSE) {
				// Form validation failed
				$data['error'] = validation_errors();
			} else {
				// Get form inputs
				$lap_tahun = $this->input->post('lap_tahun', TRUE);

				// If yearly report, set bulan to null
				$lap_bulan = ($jenis_laporan === 'tahunan') ? null : $this->input->post('lap_bulan', TRUE);

				// Get data from model
				$data['datafilter'] = $this->M_Diska->diska($lap_bulan, $lap_tahun);
				$data['statistics'] = $this->M_Diska->getStatistics($lap_bulan, $lap_tahun);

				// Set for form persistence
				$data['selected_month'] = $lap_bulan;
				$data['selected_year'] = $lap_tahun;
				$data['jenis_laporan'] = $jenis_laporan;
			}
		}

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_diska', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * View details for a specific case
	 *
	 * @param int $id The perkara ID
	 */
	public function detail($id = null)
	{
		if (!$id) {
			show_404();
		}

		// Implementation for the detail view
		// ...
	}

	/**
	 * Export data to Excel
	 */
	public function export()
	{
		// Implementation for export functionality
		// ...
	}
}
