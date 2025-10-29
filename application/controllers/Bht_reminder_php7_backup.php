<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Controller untuk Sistem Pengingat dan Dashboard BHT
 * 
 * PEMBELAJARAN KONSEP MVC:
 * - Controller bertugas menerima request dari user
 * - Memanggil Model untuk mendapatkan data dari database  
 * - Mengirim data ke View untuk ditampilkan
 * - Tidak melakukan query database langsung (itu tugas Model)
 */
class Bht_reminder extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Load semua kebutuhan
        $this->load->model('M_bht_reminder'); // Load model yang sudah kita buat
        $this->load->helper(['url', 'date']);   // Helper untuk URL dan tanggal
        $this->load->library('session');       // Session untuk notifikasi

        // Contoh sederhana authentication check
        // (Anda bisa sesuaikan dengan sistem login Anda)
        // if (!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
    }

    /**
     * HALAMAN UTAMA DASHBOARD BHT REMINDER
     * 
     * URL: http://localhost/Monitoring_BHT/index.php/bht_reminder
     * atau: http://localhost/Monitoring_BHT/index.php/bht_reminder/index
     */
    public function index()
    {
        try {
            // STEP 1: Kumpulkan semua data yang dibutuhkan dari Model

            // Data pengingat otomatis (perkara yang mendekati deadline)
            $reminders = $this->M_bht_reminder->get_automatic_reminders(3); // 3 hari sebelum deadline

            // Data laporan rekap bulan ini
            $current_year = date('Y');
            $current_month = date('m');
            $monthly_report = $this->M_bht_reminder->get_bht_status_report($current_year, $current_month);

            // Data untuk chart (grafik tahunan)
            $chart_data = $this->M_bht_reminder->get_monthly_chart_data($current_year);

            // Data distribusi jenis perkara untuk pie chart
            $case_distribution = $this->M_bht_reminder->get_case_type_distribution($current_year);

            // STEP 2: Siapkan data untuk dikirim ke View
            $data = [
                // Data untuk tabel pengingat
                'reminders' => $reminders,
                'reminder_count' => count($reminders),

                // Data untuk cards/widget statistik
                'monthly_report' => $monthly_report,
                'current_month_name' => $this->get_month_name($current_month),
                'current_year' => $current_year,

                // Data untuk charts (akan dikonversi ke JSON di view)
                'chart_data' => $chart_data,
                'case_distribution' => $case_distribution,

                // Data tambahan untuk UI
                'page_title' => 'Dashboard BHT - Pengingat & Statistik',
                'breadcrumb' => [
                    'Dashboard' => base_url('dashboard'),
                    'BHT Reminder' => '#'
                ],

                // Config untuk berbagai fitur
                'show_urgent_only' => $this->input->get('urgent') == '1',
                'selected_year' => $current_year
            ];

            // STEP 3: Load template dan view
            // Ini mengikuti pola template system seperti dashboard sebelumnya
            $this->load->view('template/new_header', $data);
            $this->load->view('template/new_sidebar');
            $this->load->view('v_bht_reminder', $data);  // View utama kita
            $this->load->view('template/new_footer');
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error yang user-friendly
            log_message('error', 'Error in Bht_reminder::index: ' . $e->getMessage());

            $data['error_message'] = 'Terjadi kesalahan saat memuat dashboard. Silakan coba lagi.';
            $data['page_title'] = 'Error - Dashboard BHT';

            $this->load->view('template/new_header', $data);
            $this->load->view('template/new_sidebar');
            $this->load->view('v_bht_reminder', $data);
            $this->load->view('template/new_footer');
        }
    }

    /**
     * AJAX: Mendapatkan data pengingat yang difilter
     * 
     * URL: http://localhost/Monitoring_BHT/index.php/bht_reminder/get_filtered_reminders
     * Method: GET/POST
     * Parameters: 
     * - days_before: berapa hari sebelum deadline (default: 3)
     * - status: URGENT, WARNING, TERLAMBAT, atau ALL
     */
    public function get_filtered_reminders()
    {
        try {
            // Ambil parameter dari request
            $days_before = $this->input->get_post('days_before') ?: 3;
            $status_filter = $this->input->get_post('status') ?: 'ALL';

            // Dapatkan data dari model
            $reminders = $this->M_bht_reminder->get_automatic_reminders($days_before);

            // Filter berdasarkan status jika diminta
            if ($status_filter !== 'ALL') {
                $reminders = array_filter($reminders, function ($reminder) use ($status_filter) {
                    return $reminder->status_prioritas === $status_filter;
                });
                $reminders = array_values($reminders); // Reset array keys
            }

            // Kembalikan data dalam format JSON untuk AJAX
            $response = [
                'success' => true,
                'data' => $reminders,
                'count' => count($reminders),
                'filter_applied' => [
                    'days_before' => $days_before,
                    'status' => $status_filter
                ]
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            // Return error dalam format JSON
            $response = [
                'success' => false,
                'error' => 'Gagal memuat data pengingat',
                'message' => $e->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * AJAX: Mendapatkan laporan rekap untuk bulan/tahun tertentu
     * 
     * URL: http://localhost/Monitoring_BHT/index.php/bht_reminder/get_monthly_report
     * Parameters: year, month
     */
    public function get_monthly_report()
    {
        try {
            $year = $this->input->get_post('year') ?: date('Y');
            $month = $this->input->get_post('month') ?: date('m');

            $report = $this->M_bht_reminder->get_bht_status_report($year, $month);

            $response = [
                'success' => true,
                'data' => $report,
                'period' => [
                    'year' => $year,
                    'month' => $month,
                    'month_name' => $this->get_month_name($month)
                ]
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'error' => 'Gagal memuat laporan rekap',
                'message' => $e->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * AJAX: Mendapatkan data chart untuk tahun tertentu
     * 
     * URL: http://localhost/Monitoring_BHT/index.php/bht_reminder/get_chart_data
     * Parameters: year
     */
    public function get_chart_data()
    {
        try {
            $year = $this->input->get_post('year') ?: date('Y');

            // Dapatkan data chart dari model
            $chart_data = $this->M_bht_reminder->get_monthly_chart_data($year);
            $case_distribution = $this->M_bht_reminder->get_case_type_distribution($year);

            $response = [
                'success' => true,
                'monthly_data' => $chart_data,
                'case_distribution' => $case_distribution,
                'year' => $year
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'error' => 'Gagal memuat data grafik',
                'message' => $e->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Menandai pengingat sebagai sudah ditangani
     * 
     * URL: http://localhost/Monitoring_BHT/index.php/bht_reminder/mark_handled
     * Method: POST
     * Parameters: perkara_id, note
     */
    public function mark_handled()
    {
        try {
            $perkara_id = $this->input->post('perkara_id');
            $note = $this->input->post('note') ?: '';

            if (empty($perkara_id)) {
                throw new Exception('Perkara ID tidak boleh kosong');
            }

            $result = $this->M_bht_reminder->mark_reminder_handled($perkara_id, $note);

            if ($result) {
                $response = [
                    'success' => true,
                    'message' => 'Pengingat berhasil ditandai sebagai sudah ditangani'
                ];
            } else {
                throw new Exception('Gagal menandai pengingat');
            }

            header('Content-Type: application/json');
            echo json_encode($response);
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'error' => 'Gagal menandai pengingat',
                'message' => $e->getMessage()
            ];

            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Export laporan ke Excel/PDF
     * 
     * URL: http://localhost/Monitoring_BHT/index.php/bht_reminder/export_report
     * Parameters: format (excel/pdf), year, month
     */
    public function export_report()
    {
        try {
            $format = $this->input->get('format') ?: 'excel';
            $year = $this->input->get('year') ?: date('Y');
            $month = $this->input->get('month') ?: date('m');

            // Dapatkan data untuk export
            $report = $this->M_bht_reminder->get_bht_status_report($year, $month);
            $reminders = $this->M_bht_reminder->get_automatic_reminders(30); // Semua dalam 30 hari

            if ($format === 'excel') {
                $this->export_to_excel($report, $reminders, $year, $month);
            } else if ($format === 'pdf') {
                $this->export_to_pdf($report, $reminders, $year, $month);
            } else {
                throw new Exception('Format export tidak didukung');
            }
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal export laporan: ' . $e->getMessage());
            redirect('bht_reminder');
        }
    }

    // ========== PRIVATE HELPER METHODS ==========

    /**
     * Mengubah nomor bulan menjadi nama bulan dalam bahasa Indonesia
     */
    private function get_month_name($month)
    {
        $months = array(
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
        );

        $month_key = sprintf('%02d', $month);
        return isset($months[$month_key]) ? $months[$month_key] : 'Tidak Diketahui';
    }

    /**
     * Export laporan ke format Excel
     * (Membutuhkan library PHPExcel yang sudah ada di folder PHPExcel-1.8)
     */
    private function export_to_excel($report, $reminders, $year, $month)
    {
        // Load PHPExcel library
        require_once APPPATH . 'PHPExcel-1.8/Classes/PHPExcel.php';

        $excel = new PHPExcel();
        $excel->setActiveSheetIndex(0);
        $sheet = $excel->getActiveSheet();

        // Set judul dan header
        $month_name = $this->get_month_name($month);
        $sheet->setTitle("Laporan BHT $month_name $year");

        // Isi data... (implementasi detail bisa ditambahkan kemudian)
        $sheet->setCellValue('A1', "Laporan BHT - $month_name $year");

        // Set headers untuk download
        $filename = "Laporan_BHT_{$year}_{$month}.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $writer->save('php://output');
    }

    /**
     * Export laporan ke format PDF
     * (Bisa menggunakan library seperti TCPDF atau mPDF)
     */
    private function export_to_pdf($report, $reminders, $year, $month)
    {
        // Implementasi PDF export bisa ditambahkan kemudian
        // Untuk sementara, tampilkan pesan
        $this->session->set_flashdata('info', 'Fitur export PDF sedang dalam pengembangan');
        redirect('bht_reminder');
    }
}
