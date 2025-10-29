<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller untuk Sistem Pengingat dan Dashboard BHT
 * 
 * VERSI PHP 5.6 COMPATIBLE
 * - Menggunakan array() syntax instead of []
 * - Menggunakan isset() ternary instead of ??
 * - Compatible dengan XAMPP PHP 5.6.40
 */
class Bht_reminder extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Load semua kebutuhan
        $this->load->model('M_bht_reminder'); // Load model yang sudah kita buat
        $this->load->helper(array('url', 'date'));   // Helper untuk URL dan tanggal - PHP 5.6 syntax
        $this->load->library('session');       // Session untuk notifikasi
    }

    /**
     * HALAMAN UTAMA DASHBOARD BHT REMINDER
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
            
            // STEP 2: Siapkan data untuk dikirim ke View (PHP 5.6 syntax)
            $data = array(
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
                'breadcrumb' => array(
                    'Dashboard' => base_url('dashboard'),
                    'BHT Reminder' => '#'
                ),
                
                // Config untuk berbagai fitur
                'show_urgent_only' => ($this->input->get('urgent') == '1'),
                'selected_year' => $current_year
            );
            
            // STEP 3: Load template dan view
            $this->load->view('template/new_header', $data);
            $this->load->view('template/new_sidebar');
            $this->load->view('v_bht_reminder', $data);  // View utama kita
            $this->load->view('template/new_footer');
            
        } catch (Exception $e) {
            // Jika terjadi error, tampilkan pesan error yang user-friendly
            log_message('error', 'Error in Bht_reminder::index: ' . $e->getMessage());
            
            $data = array();
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
     */
    public function get_filtered_reminders()
    {
        try {
            // Ambil parameter dari request (PHP 5.6 compatible)
            $days_before = $this->input->get_post('days_before');
            if (empty($days_before)) $days_before = 3;
            
            $status_filter = $this->input->get_post('status');
            if (empty($status_filter)) $status_filter = 'ALL';
            
            // Dapatkan data dari model
            $reminders = $this->M_bht_reminder->get_automatic_reminders($days_before);
            
            // Filter berdasarkan status jika diminta
            if ($status_filter !== 'ALL') {
                $filtered_reminders = array();
                foreach ($reminders as $reminder) {
                    if ($reminder->status_prioritas === $status_filter) {
                        $filtered_reminders[] = $reminder;
                    }
                }
                $reminders = $filtered_reminders;
            }
            
            // Kembalikan data dalam format JSON untuk AJAX (PHP 5.6 syntax)
            $response = array(
                'success' => true,
                'data' => $reminders,
                'count' => count($reminders),
                'filter_applied' => array(
                    'days_before' => $days_before,
                    'status' => $status_filter
                )
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            // Return error dalam format JSON
            $response = array(
                'success' => false,
                'error' => 'Gagal memuat data pengingat',
                'message' => $e->getMessage()
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * AJAX: Mendapatkan laporan rekap untuk bulan/tahun tertentu
     */
    public function get_monthly_report()
    {
        try {
            $year = $this->input->get_post('year');
            if (empty($year)) $year = date('Y');
            
            $month = $this->input->get_post('month');
            if (empty($month)) $month = date('m');
            
            $report = $this->M_bht_reminder->get_bht_status_report($year, $month);
            
            $response = array(
                'success' => true,
                'data' => $report,
                'period' => array(
                    'year' => $year,
                    'month' => $month,
                    'month_name' => $this->get_month_name($month)
                )
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'error' => 'Gagal memuat laporan rekap',
                'message' => $e->getMessage()
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * AJAX: Mendapatkan data chart untuk tahun tertentu
     */
    public function get_chart_data()
    {
        try {
            $year = $this->input->get_post('year');
            if (empty($year)) $year = date('Y');
            
            // Dapatkan data chart dari model
            $chart_data = $this->M_bht_reminder->get_monthly_chart_data($year);
            $case_distribution = $this->M_bht_reminder->get_case_type_distribution($year);
            
            $response = array(
                'success' => true,
                'monthly_data' => $chart_data,
                'case_distribution' => $case_distribution,
                'year' => $year
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'error' => 'Gagal memuat data grafik',
                'message' => $e->getMessage()
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Menandai pengingat sebagai sudah ditangani
     */
    public function mark_handled()
    {
        try {
            $perkara_id = $this->input->post('perkara_id');
            $note = $this->input->post('note');
            if (empty($note)) $note = '';
            
            if (empty($perkara_id)) {
                throw new Exception('Perkara ID tidak boleh kosong');
            }
            
            $result = $this->M_bht_reminder->mark_reminder_handled($perkara_id, $note);
            
            if ($result) {
                $response = array(
                    'success' => true,
                    'message' => 'Pengingat berhasil ditandai sebagai sudah ditangani'
                );
            } else {
                throw new Exception('Gagal menandai pengingat');
            }
            
            header('Content-Type: application/json');
            echo json_encode($response);
            
        } catch (Exception $e) {
            $response = array(
                'success' => false,
                'error' => 'Gagal menandai pengingat',
                'message' => $e->getMessage()
            );
            
            header('Content-Type: application/json');
            echo json_encode($response);
        }
    }

    /**
     * Export laporan ke Excel/PDF
     */
    public function export_report()
    {
        try {
            $format = $this->input->get('format');
            if (empty($format)) $format = 'excel';
            
            $year = $this->input->get('year');
            if (empty($year)) $year = date('Y');
            
            $month = $this->input->get('month');
            if (empty($month)) $month = date('m');
            
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
     * PHP 5.6 COMPATIBLE VERSION
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
        
        // Isi data sederhana
        $sheet->setCellValue('A1', "Laporan BHT - $month_name $year");
        $sheet->setCellValue('A3', 'Total Perkara Putus:');
        $sheet->setCellValue('B3', $report->total_perkara_putus);
        $sheet->setCellValue('A4', 'BHT Selesai:');
        $sheet->setCellValue('B4', $report->bht_selesai);
        $sheet->setCellValue('A5', 'BHT Belum:');
        $sheet->setCellValue('B5', $report->bht_belum);
        
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
     */
    private function export_to_pdf($report, $reminders, $year, $month)
    {
        // Implementasi PDF export bisa ditambahkan kemudian
        $this->session->set_flashdata('info', 'Fitur export PDF sedang dalam pengembangan');
        redirect('bht_reminder');
    }
}
?>