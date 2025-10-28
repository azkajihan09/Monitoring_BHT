<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ecourt_monitoring extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_ecourt_monitoring");
    }

    public function index()
    {
        $data = [];

        // Get filters from form submission
        $tahun = $this->input->post('tahun', TRUE);

        // Default to current year if not provided
        if (empty($tahun)) {
            $tahun = date('Y');
        }

        // Get summary data
        $data['summary'] = $this->M_ecourt_monitoring->get_summary($tahun);

        // Get unregistered cases - cases that are still pending registration
        $data['unregistered'] = $this->M_ecourt_monitoring->get_unregistered($tahun);

        // Get pending cases - cases waiting for judge assignment
        $data['pending_pmh'] = $this->M_ecourt_monitoring->get_pending_judge_assignment($tahun);

        // Get cases waiting for decision
        $data['pending_decision'] = $this->M_ecourt_monitoring->get_pending_decision($tahun);

        // Get cases pending document upload
        $data['pending_upload'] = $this->M_ecourt_monitoring->get_pending_document_upload($tahun);

        // Set selected year
        $data['selected_year'] = $tahun;

        // Load views
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_ecourt_monitoring', $data);
        $this->load->view('template/new_footer');
    }

    public function timeline($perkara_id = NULL)
    {
        if (!$perkara_id) {
            show_404();
            return;
        }

        // Get case data
        $data['case'] = $this->M_ecourt_monitoring->get_case($perkara_id);

        if (!$data['case']) {
            show_404();
            return;
        }

        // Get timeline data
        $data['timeline'] = $this->M_ecourt_monitoring->get_timeline($perkara_id);

        // Load views
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_ecourt_timeline', $data);
        $this->load->view('template/new_footer');
    }
}
