<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Perkara extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_perkara");
	}

	/**
	 * Display timeline for a specific case
	 * 
	 * @param int $perkara_id Case ID
	 * @return void
	 */
	public function timeline($perkara_id = NULL)
	{
		// Check if perkara_id is provided
		if (!$perkara_id) {
			show_404();
			return;
		}

		// Get case details
		$data['perkara'] = $this->M_perkara->get_detail($perkara_id);

		// If case not found, show 404
		if (!$data['perkara']) {
			show_404();
			return;
		}

		// Get timeline events for this case
		$data['timeline'] = $this->M_perkara->get_timeline($perkara_id);

		// Load views
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_perkara_timeline', $data);
		$this->load->view('template/new_footer');
	}
}
