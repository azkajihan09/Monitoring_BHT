<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Odm extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_odm');
	}

	public function index()
	{
		// Ambil parameter dari form
		$lap_bulan = $this->input->post('lap_bulan');
		$lap_tahun = $this->input->post('lap_tahun');

		// Default jika tidak ada input
		if (empty($lap_bulan) || empty($lap_tahun)) {
			$lap_bulan = date('m');
			$lap_tahun = date('Y');
		}

		// Data untuk view
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;

		// PERBAIKAN: Ganti odm() dengan get_odm_data()
		$data['datafilter'] = $this->M_odm->get_odm_data($lap_bulan, $lap_tahun);

		// Ambil statistik
		$data['stats'] = $this->M_odm->get_statistics($lap_bulan, $lap_tahun);

		// Load view
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_odm', $data);
		$this->load->view('template/new_footer');
	}

	/**
	 * Export ODM data to Excel
	 *
	 * @param string $lap_bulan Month (optional)
	 * @param string $lap_tahun Year (optional)
	 * @return void
	 */
	public function export_excel($lap_bulan = null, $lap_tahun = null)
	{
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

		// Default to current month/year if still empty
		if (empty($lap_bulan)) {
			$lap_bulan = date('m');
		}

		if (empty($lap_tahun)) {
			$lap_tahun = date('Y');
		}

		// Call model's export method
		$this->M_odm->export_excel($lap_bulan, $lap_tahun);
	}
}
