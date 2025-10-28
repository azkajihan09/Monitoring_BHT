<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Penyerahan_ac extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_Penyerahan_ac");
		$this->load->library('form_validation');
	}

	public function index()
	{
		// Set default values
		$data = [
			'datafilter' => [],
			'statistics' => null,
		];

		// Current month and year as default
		$data['selected_bulan'] = date('m');
		$data['selected_tahun'] = date('Y');

		if ($this->input->method() === 'post') {
			$lap_bulan = $this->input->post('lap_bulan', TRUE);
			$lap_tahun = $this->input->post('lap_tahun', TRUE);

			$this->form_validation->set_rules('lap_bulan', 'Bulan', 'required|trim|in_list[01,02,03,04,05,06,07,08,09,10,11,12]');
			$this->form_validation->set_rules('lap_tahun', 'Tahun', 'required|trim|numeric|min_length[4]|max_length[4]');

			if ($this->form_validation->run() === FALSE) {
				$data['error'] = validation_errors();
			} else {
				$data['datafilter'] = $this->M_Penyerahan_ac->penyerahan_ac($lap_bulan, $lap_tahun);
				$data['statistics'] = $this->M_Penyerahan_ac->getStatistics($lap_bulan, $lap_tahun);
				$data['selected_bulan'] = $lap_bulan;
				$data['selected_tahun'] = $lap_tahun;
			}
		}

		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_penyerahan_ac', $data);
		$this->load->view('template/new_footer');
	}
}
