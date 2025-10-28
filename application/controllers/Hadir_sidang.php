<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Hadir_sidang extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_Hadir_sidang");
    }

   public function index()
    {
        
		$lap_bulan = $this->input->post('lap_bulan');
        $lap_tahun = $this->input->post('lap_tahun');

		$data['datafilter'] = $this->M_Hadir_sidang->hadir_sidang($lap_bulan, $lap_tahun);
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_hadir_sidang', $data);
		$this->load->view('template/new_footer');	
    }
}
