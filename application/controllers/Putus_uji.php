<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Putus_uji extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_putus_uji');
	}

	public function index()
	{
		// Set default values
		$jenis_perkara = $this->input->post('jenis_perkara') ?: 'Pdt.G';
		$lap_bulan = $this->input->post('lap_bulan') ?: date('m');
		$lap_tahun = $this->input->post('lap_tahun') ?: date('Y');
		
		$data['putus'] = $this->M_putus_uji->putus($jenis_perkara, $lap_bulan, $lap_tahun);
		$data['statistics'] = $this->M_putus_uji->get_statistics($jenis_perkara, $lap_bulan, $lap_tahun);
		$data['jenis_perkara'] = $jenis_perkara;
		$data['lap_bulan'] = $lap_bulan;
		$data['lap_tahun'] = $lap_tahun;
		
		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_putus_uji', $data);
		$this->load->view('template/new_footer');
	}
}
