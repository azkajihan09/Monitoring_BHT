<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cerai_kua extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("M_cerai_kua");
	}

	public function index()
	{
		$data = [];

		// Define month names for display
		$data['nama_bulan'] = [
			'01' => 'Januari',
			'02' => 'Februari',
			'03' => 'Maret',
			'04' => 'April',
			'05' => 'Mei',
			'06' => 'Juni',
			'07' => 'Juli',
			'08' => 'Agustus',
			'09' => 'September',
			'10' => 'Oktober',
			'11' => 'November',
			'12' => 'Desember'
		];

		// Periksa adanya data form yang disubmit
		if ($this->input->post('btn')) {
			$lap_bulan = $this->input->post('lap_bulan', TRUE);
			$lap_tahun = $this->input->post('lap_tahun', TRUE);

			// Validasi input
			if (!empty($lap_bulan) && !empty($lap_tahun)) {
				// Simpan parameter filter ke data
				$data['lap_bulan'] = $lap_bulan;
				$data['lap_tahun'] = $lap_tahun;

				// Ambil data dari model
				$data['datafilter'] = $this->M_cerai_kua->cerai_kua($lap_bulan, $lap_tahun);
				$data['stats'] = $this->M_cerai_kua->get_statistics($lap_bulan, $lap_tahun);

				// Dapatkan data KUA untuk grafik dan tabel
				$kua_stats = $this->M_cerai_kua->get_kua_distribution($lap_bulan, $lap_tahun);
				$data['kua_counts'] = array_map(function ($item) {
					return (object) [
						'kua_tempat_nikah' => $item['kua_tempat_nikah'] ?: 'Tidak Tercatat',
						'total' => $item['total']
					];
				}, $kua_stats);
			} else {
				// Jika parameter tidak valid, kosongkan data
				$data['datafilter'] = [];
			}
		} else {
			// Default saat pertama kali halaman dibuka
			$data['datafilter'] = [];
		}

		// Tampilkan view
		$this->load->view('template/new_header');
		$this->load->view('template/new_sidebar');
		$this->load->view('v_cerai_kua', $data);
		$this->load->view('template/new_footer');
	}
}
