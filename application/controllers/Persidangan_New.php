<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Persidangan_New extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_Persidangan_New");
        $this->load->library('pagination');
        $this->load->helper('url');
    }

    /**
     * Main index page
     */
    public function index()
    {
        // Init data array to pass to view
        $data = [];

        // Check for filters
        $filters = [];

        // Date filter - defaults to today if not provided
        if ($this->input->get('tanggal_sidang')) {
            $filters['tanggal_sidang'] = $this->input->get('tanggal_sidang');
        } else if ($this->input->get('tanggal_mulai') && $this->input->get('tanggal_akhir')) {
            $filters['tanggal_mulai'] = $this->input->get('tanggal_mulai');
            $filters['tanggal_akhir'] = $this->input->get('tanggal_akhir');
        } else {
            $filters['tanggal_sidang'] = date('Y-m-d'); // Default to today
        }

        // Jurusita filter - update to use jurusita_id
        if ($this->input->get('jurusita')) {
            $filters['jurusita'] = $this->input->get('jurusita');
        }

        // Room filter
        if ($this->input->get('ruangan_id')) {
            $filters['ruangan_id'] = $this->input->get('ruangan_id');
        }

        // Status filter
        if ($this->input->get('status')) {
            $filters['status'] = $this->input->get('status');
        }

        // Search query
        if ($this->input->get('search')) {
            $filters['search'] = $this->input->get('search');
        }

        // Set up pagination
        $config['base_url'] = site_url('Persidangan_New/index');
        $config['total_rows'] = $this->M_Persidangan_New->count_jadwal_sidang($filters);
        $config['per_page'] = 15;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        // Bootstrap 4 pagination styling
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&raquo';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');

        $this->pagination->initialize($config);

        // Get items for the current page
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $data['sidang_list'] = $this->M_Persidangan_New->get_jadwal_sidang($filters, $config['per_page'], $page);

        // Calculate day statistics
        $data['stats'] = $this->M_Persidangan_New->get_statistics('day');

        // Get filter options
        $data['jurusita_list'] = $this->M_Persidangan_New->get_jurusita();
        $data['ruangan_list'] = $this->M_Persidangan_New->get_ruangan();

        // Current active filters for view
        $data['filters'] = $filters;

        // Pass filter parameters to view
        $data['pagination'] = $this->pagination->create_links();

        // View mode: list (default), calendar, or card view
        $data['view_mode'] = $this->input->get('view') ? $this->input->get('view') : 'list';

        // Load views
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_persidangan_new', $data);
        $this->load->view('template/new_footer');
    }

    /**
     * Calendar view
     */
    public function calendar()
    {
        $data = [];

        // Get current month/year or use provided values
        $month = $this->input->get('month') ? $this->input->get('month') : date('m');
        $year = $this->input->get('year') ? $this->input->get('year') : date('Y');

        $data['month'] = $month;
        $data['year'] = $year;
        $data['month_name'] = date('F', mktime(0, 0, 0, $month, 10));

        // Get calendar data
        $data['calendar_data'] = $this->M_Persidangan_New->get_calendar_data($month, $year);

        // Get filter options
        $data['jurusita_list'] = $this->M_Persidangan_New->get_jurusita();
        $data['ruangan_list'] = $this->M_Persidangan_New->get_ruangan();

        // Load views
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_persidangan_calendar', $data);
        $this->load->view('template/new_footer');
    }

    /**
     * Dashboard view with statistics
     */
    public function dashboard()
    {
        $data = [];

        // Get period from query string (day, week, month)
        $period = $this->input->get('period') ? $this->input->get('period') : 'day';
        $data['period'] = $period;

        // Get statistics
        $data['stats'] = $this->M_Persidangan_New->get_statistics($period);

        // Today's sessions
        $filters = ['tanggal_sidang' => date('Y-m-d')];
        $data['today_sessions'] = $this->M_Persidangan_New->get_jadwal_sidang($filters, 10);

        // Load views
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_persidangan_dashboard', $data);
        $this->load->view('template/new_footer');
    }

    /**
     * Case detail view
     */
    public function detail($perkara_id)
    {
        if (!$perkara_id) {
            show_404();
            return;
        }

        $data = [];

        // Get case details
        $data['detail'] = $this->M_Persidangan_New->get_detail_perkara($perkara_id);

        // Get session history
        $data['riwayat_sidang'] = $this->M_Persidangan_New->get_riwayat_sidang($perkara_id);

        // Load views
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_persidangan_detail', $data);
        $this->load->view('template/new_footer');
    }

    /**
     * Export to Excel
     */
    public function export_excel()
    {
        // Init filters as in index()
        $filters = [];

        // Apply the same filters as in the index method
        if ($this->input->get('tanggal_sidang')) {
            $filters['tanggal_sidang'] = $this->input->get('tanggal_sidang');
        } else if ($this->input->get('tanggal_mulai') && $this->input->get('tanggal_akhir')) {
            $filters['tanggal_mulai'] = $this->input->get('tanggal_mulai');
            $filters['tanggal_akhir'] = $this->input->get('tanggal_akhir');
        } else {
            $filters['tanggal_sidang'] = date('Y-m-d'); // Default to today
        }

        if ($this->input->get('jurusita')) {
            $filters['jurusita'] = $this->input->get('jurusita');
        }

        if ($this->input->get('ruangan_id')) {
            $filters['ruangan_id'] = $this->input->get('ruangan_id');
        }

        if ($this->input->get('search')) {
            $filters['search'] = $this->input->get('search');
        }

        // Get all data without pagination
        $data = $this->M_Persidangan_New->get_jadwal_sidang($filters);

        // Set the file name
        if (!empty($filters['tanggal_sidang'])) {
            $filename = 'Jadwal_Sidang_' . $filters['tanggal_sidang'] . '_' . date('YmdHis') . '.xls';
        } else {
            $filename = 'Jadwal_Sidang_' . $filters['tanggal_mulai'] . '_sd_' . $filters['tanggal_akhir'] . '_' . date('YmdHis') . '.xls';
        }

        // Set headers for Excel download
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max-age=0');

        // Start output buffer
        ob_start();

        echo '<table border="1">';
        echo '<tr>';
        echo '<th>No</th>';
        echo '<th>Nomor Perkara</th>';
        echo '<th>Tanggal Sidang</th>';
        echo '<th>Jam Sidang</th>';
        echo '<th>Agenda</th>';
        echo '<th>Majelis Hakim</th>';
        echo '<th>Panitera</th>';
        echo '<th>Jurusita</th>';
        echo '<th>Ruangan</th>';
        echo '<th>Penggugat</th>';
        echo '<th>Tergugat</th>';
        echo '<th>Status</th>';
        echo '</tr>';

        $no = 1;
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $row->nomor_perkara . '</td>';
            echo '<td>' . date('d-m-Y', strtotime($row->tanggal_sidang)) . '</td>';
            echo '<td>' . date('H:i', strtotime($row->jam_sidang)) . '</td>';
            echo '<td>' . $row->agenda . '</td>';
            echo '<td>' . str_replace("<br>", ", ", $row->majelis_hakim) . '</td>';
            echo '<td>' . $row->panitera_nama . '</td>';
            echo '<td>' . $row->jurusita_nama . '</td>';
            echo '<td>' . $row->ruangan . '</td>';
            echo '<td>' . $row->nama_p . '</td>';
            echo '<td>' . $row->nama_t . '</td>';

            // Determine status
            $status = 'Aktif';
            if (!empty($row->tanggal_putusan)) {
                $status = 'Putus';
            } else if ($row->ditunda == 'Y') {
                $status = 'Ditunda';
            }
            echo '<td>' . $status . '</td>';

            echo '</tr>';
        }
        echo '</table>';

        $content = ob_get_contents();
        ob_end_clean();

        echo $content;
        exit;
    }
}
