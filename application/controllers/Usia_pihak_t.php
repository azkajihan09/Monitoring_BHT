<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usia_pihak_t extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_usia_pihak_t");
    }

   public function index()
    {
        $jenis_kelamin = $this->input->post('jenis_kelamin');
        $lap_bulan = $this->input->post('lap_bulan');
        $lap_tahun = $this->input->post('lap_tahun');
        $data['datafilter'] = $this->M_usia_pihak_t->Usia_pihak_t($jenis_kelamin, $lap_bulan, $lap_tahun);
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_usia_pihak_t', $data);
        $this->load->view('template/new_footer');   
    }
}
