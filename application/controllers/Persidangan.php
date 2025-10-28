<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Persidangan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_Persidangan");
    }

   public function index()
    {
		$tanggal_sidang = $this->input->post('tanggal_sidang');
		$jurusita = $this->input->post('jurusita_nama');
		$data['datafilter'] = $this->M_Persidangan->jadwal_sidang_js($jurusita, $tanggal_sidang);
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_persidangan', $data);
		$this->load->view('template/footer');	
    }

    public function detail($idperkara)
    {
        $this->load->model('M_Persidangan');
        $detail = $this->M_Persidangan->detail_data($idperkara);
        $data['detail'] = $detail;
        $this->load->view('template/header');
        $this->load->view('template/sidebar');
        $this->load->view('detail_persidangan', $data);
        $this->load->view('template/footer');

    }
}
