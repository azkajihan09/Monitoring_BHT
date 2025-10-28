<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_salput extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_reg_salput");
    }

   public function index()
    {
		$lap_bulan = $this->input->post('lap_bulan');
        $lap_tahun = $this->input->post('lap_tahun');
		$data['datafilter'] = $this->M_reg_salput->reg_salput($lap_bulan, $lap_tahun);
        //$data['datafilter2'] = $this->M_rekap_ecourt->diterima_p($lap_bulan, $lap_tahun);
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_reg_salput', $data);
		$this->load->view('template/new_footer');	
    }
}
