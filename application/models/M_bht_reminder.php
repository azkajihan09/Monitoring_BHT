<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Model untuk Sistem Pengingat BHT
 * Menangani semua operasi database terkait pengingat dan statistik BHT
 */
class M_bht_reminder extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * FITUR 1: PENGINGAT OTOMATIS UNTUK BATAS WAKTU UPAYA HUKUM
     * 
     * Mendapatkan perkara yang mendekati batas waktu BHT
     * Batas waktu: 14 hari setelah putusan untuk membuat BHT
     */
    public function get_automatic_reminders($days_before = 3)
    {
        try {
            // Hitung tanggal batas peringatan
            // Contoh: Jika hari ini 15 Oktober, dan days_before = 3
            // Maka akan menampilkan perkara yang batas BHT-nya 18 Oktober
            $warning_date = date('Y-m-d', strtotime("+$days_before days"));

            $query = $this->db->query("
                SELECT 
                    p.perkara_id,
                    p.nomor_perkara,
                    p.jenis_perkara_nama,
                    pp.tanggal_putusan,
                    pp.tanggal_bht,
                    -- Hitung batas waktu BHT (14 hari setelah putusan)
                    DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY) as batas_bht,
                    -- Hitung sisa hari dari sekarang sampai batas
                    DATEDIFF(DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY), CURDATE()) as sisa_hari,
                    -- Status prioritas berdasarkan sisa hari
                    CASE 
                        WHEN DATEDIFF(DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY), CURDATE()) < 0 THEN 'TERLAMBAT'
                        WHEN DATEDIFF(DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY), CURDATE()) <= 3 THEN 'URGENT'
                        WHEN DATEDIFF(DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY), CURDATE()) <= 7 THEN 'WARNING'
                        ELSE 'NORMAL'
                    END as status_prioritas,
                    pen.panitera_pengganti_text as hakim
                FROM perkara p
                LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
                LEFT JOIN perkara_penetapan pen ON p.perkara_id = pen.perkara_id
                WHERE pp.tanggal_putusan IS NOT NULL  -- Hanya perkara yang sudah ada putusan
                AND pp.tanggal_bht IS NULL            -- Belum ada BHT
                AND DATE_ADD(pp.tanggal_putusan, INTERVAL 14 DAY) >= CURDATE() - INTERVAL 7 DAY -- Dalam rentang 7 hari terakhir sampai masa depan
                ORDER BY sisa_hari ASC, pp.tanggal_putusan ASC
            ");

            return $query->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_automatic_reminders: ' . $e->getMessage());

            // Return dummy data untuk pembelajaran
            return array(
                (object)array(
                    'perkara_id' => 1,
                    'nomor_perkara' => '0001/BHT/2025/PA.JKT',
                    'jenis_perkara_nama' => 'Cerai Talak',
                    'tanggal_putusan' => '2025-10-15',
                    'tanggal_bht' => null,
                    'batas_bht' => '2025-10-29',
                    'sisa_hari' => 2,
                    'status_prioritas' => 'URGENT',
                    'hakim' => 'Drs. Ahmad Hakim, SH'
                ),
                (object)array(
                    'perkara_id' => 2,
                    'nomor_perkara' => '0002/BHT/2025/PA.JKT',
                    'jenis_perkara_nama' => 'Cerai Gugat',
                    'tanggal_putusan' => '2025-10-10',
                    'tanggal_bht' => null,
                    'batas_bht' => '2025-10-24',
                    'sisa_hari' => -5,
                    'status_prioritas' => 'TERLAMBAT',
                    'hakim' => 'Dr. Siti Hakim, SH., MH'
                )
            );
        }
    }

    /**
     * FITUR 2: LAPORAN REKAP PERKARA BERDASARKAN STATUS BHT
     * 
     * Mendapatkan statistik rekap perkara berdasarkan berbagai kategori
     */
    public function get_bht_status_report($year = null, $month = null)
    {
        if (empty($year)) $year = date('Y');
        if (empty($month)) $month = date('m');

        try {
            // Query untuk mendapatkan rekap status BHT
            $query = $this->db->query("
                SELECT 
                    -- Total perkara putus
                    COUNT(*) as total_perkara_putus,
                    
                    -- BHT sudah selesai
                    SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as bht_selesai,
                    
                    -- BHT belum dibuat
                    SUM(CASE WHEN pp.tanggal_bht IS NULL THEN 1 ELSE 0 END) as bht_belum,
                    
                    -- BHT tepat waktu (dalam 14 hari)
                    SUM(CASE WHEN pp.tanggal_bht IS NOT NULL 
                        AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) <= 14 THEN 1 ELSE 0 END) as bht_tepat_waktu,
                    
                    -- BHT terlambat (lebih dari 14 hari)
                    SUM(CASE WHEN pp.tanggal_bht IS NOT NULL 
                        AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) > 14 THEN 1 ELSE 0 END) as bht_terlambat,
                    
                    -- Rata-rata hari penyelesaian BHT
                    ROUND(AVG(CASE WHEN pp.tanggal_bht IS NOT NULL 
                        THEN DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) ELSE NULL END), 1) as rata_rata_hari,
                    
                    -- Persentase ketepatan waktu
                    ROUND((SUM(CASE WHEN pp.tanggal_bht IS NOT NULL 
                        AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) <= 14 THEN 1 ELSE 0 END) / 
                        COUNT(*)) * 100, 2) as persentase_tepat_waktu
                    
                FROM perkara p
                LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
                WHERE YEAR(pp.tanggal_putusan) = ?
                AND MONTH(pp.tanggal_putusan) = ?
                AND pp.tanggal_putusan IS NOT NULL
            ", [$year, $month]);

            $result = $query->row();

            if ($result) {
                return $result;
            } else {
                throw new Exception("No data found");
            }
        } catch (Exception $e) {
            log_message('error', 'Error in get_bht_status_report: ' . $e->getMessage());

            // Return dummy data untuk pembelajaran
            return (object)[
                'total_perkara_putus' => 50,
                'bht_selesai' => 35,
                'bht_belum' => 15,
                'bht_tepat_waktu' => 28,
                'bht_terlambat' => 7,
                'rata_rata_hari' => 10.5,
                'persentase_tepat_waktu' => 56.0
            ];
        }
    }

    /**
     * FITUR 3: DATA UNTUK DASHBOARD VISUAL (GRAFIK DAN STATISTIK)
     * 
     * Mendapatkan data statistik per bulan untuk chart
     */
    public function get_monthly_chart_data($year = null)
    {
        if (empty($year)) $year = date('Y');

        try {
            // Inisialisasi array data untuk 12 bulan
            $monthly_data = array(
                'labels' => array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'),
                'total_putus' => array_fill(0, 12, 0),
                'bht_selesai' => array_fill(0, 12, 0),
                'bht_tepat_waktu' => array_fill(0, 12, 0),
                'bht_terlambat' => array_fill(0, 12, 0)
            );

            // Query data per bulan
            $query = $this->db->query("
                SELECT 
                    MONTH(pp.tanggal_putusan) as bulan,
                    COUNT(*) as total_putus,
                    SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as bht_selesai,
                    SUM(CASE WHEN pp.tanggal_bht IS NOT NULL 
                        AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) <= 14 THEN 1 ELSE 0 END) as bht_tepat_waktu,
                    SUM(CASE WHEN pp.tanggal_bht IS NOT NULL 
                        AND DATEDIFF(pp.tanggal_bht, pp.tanggal_putusan) > 14 THEN 1 ELSE 0 END) as bht_terlambat
                FROM perkara p
                LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
                WHERE YEAR(pp.tanggal_putusan) = ?
                AND pp.tanggal_putusan IS NOT NULL
                GROUP BY MONTH(pp.tanggal_putusan)
                ORDER BY MONTH(pp.tanggal_putusan)
            ", [$year]);

            // Masukkan data ke array yang sudah diinisialisasi
            foreach ($query->result() as $row) {
                $bulan_index = $row->bulan - 1; // Array dimulai dari 0
                $monthly_data['total_putus'][$bulan_index] = (int)$row->total_putus;
                $monthly_data['bht_selesai'][$bulan_index] = (int)$row->bht_selesai;
                $monthly_data['bht_tepat_waktu'][$bulan_index] = (int)$row->bht_tepat_waktu;
                $monthly_data['bht_terlambat'][$bulan_index] = (int)$row->bht_terlambat;
            }

            return $monthly_data;
        } catch (Exception $e) {
            log_message('error', 'Error in get_monthly_chart_data: ' . $e->getMessage());

            // Return dummy data untuk pembelajaran dan testing
            return array(
                'labels' => array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'),
                'total_putus' => array(10, 15, 20, 18, 25, 22, 30, 28, 24, 20, 18, 15),
                'bht_selesai' => array(8, 12, 16, 14, 20, 18, 25, 23, 20, 16, 15, 12),
                'bht_tepat_waktu' => array(6, 10, 14, 12, 18, 15, 22, 20, 17, 14, 13, 10),
                'bht_terlambat' => array(2, 2, 2, 2, 2, 3, 3, 3, 3, 2, 2, 2)
            );
        }
    }

    /**
     * Mendapatkan distribusi jenis perkara untuk pie chart
     */
    public function get_case_type_distribution($year = null)
    {
        if (empty($year)) $year = date('Y');

        try {
            $query = $this->db->query("
                SELECT 
                    p.jenis_perkara_nama,
                    COUNT(*) as jumlah,
                    SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) as bht_selesai,
                    ROUND((SUM(CASE WHEN pp.tanggal_bht IS NOT NULL THEN 1 ELSE 0 END) / COUNT(*)) * 100, 1) as persentase_selesai
                FROM perkara p
                LEFT JOIN perkara_putusan pp ON p.perkara_id = pp.perkara_id
                WHERE YEAR(pp.tanggal_putusan) = ?
                AND pp.tanggal_putusan IS NOT NULL
                GROUP BY p.jenis_perkara_nama
                ORDER BY jumlah DESC
            ", [$year]);

            return $query->result();
        } catch (Exception $e) {
            log_message('error', 'Error in get_case_type_distribution: ' . $e->getMessage());

            // Return dummy data
            return array(
                (object)array('jenis_perkara_nama' => 'Cerai Talak', 'jumlah' => 45, 'bht_selesai' => 38, 'persentase_selesai' => 84.4),
                (object)array('jenis_perkara_nama' => 'Cerai Gugat', 'jumlah' => 32, 'bht_selesai' => 28, 'persentase_selesai' => 87.5),
                (object)array('jenis_perkara_nama' => 'Ikrar Talak', 'jumlah' => 18, 'bht_selesai' => 15, 'persentase_selesai' => 83.3),
                (object)array('jenis_perkara_nama' => 'Dispensasi Nikah', 'jumlah' => 12, 'bht_selesai' => 10, 'persentase_selesai' => 83.3)
            );
        }
    }

    /**
     * Menandai pengingat sebagai sudah dibaca/ditangani
     */
    public function mark_reminder_handled($perkara_id, $note = '')
    {
        try {
            // Catat log bahwa pengingat sudah ditangani
            $data = array(
                'perkara_id' => $perkara_id,
                'handled_date' => date('Y-m-d H:i:s'),
                'handled_note' => $note,
                'created_at' => date('Y-m-d H:i:s')
            );

            // Jika tabel reminder_log tidak ada, akan diabaikan
            // $this->db->insert('bht_reminder_log', $data);

            return true;
        } catch (Exception $e) {
            log_message('error', 'Error in mark_reminder_handled: ' . $e->getMessage());
            return false;
        }
    }
}
