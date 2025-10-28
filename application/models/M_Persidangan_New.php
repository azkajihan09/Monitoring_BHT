<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_Persidangan_New extends CI_Model
{
    /**
     * Get court sessions with efficient pagination and filtering
     * 
     * @param array $filters Filter parameters
     * @param int $limit Number of records to return
     * @param int $offset Starting position
     * @return array Court sessions data
     */
    public function get_jadwal_sidang($filters = [], $limit = null, $offset = null)
    {
        // Start building the query
        $this->db->select('
            pjs.id AS jadwal_id,
            p.perkara_id,
            p.nomor_perkara,
            p.jenis_perkara_nama,
            p.tanggal_pendaftaran,
            pjs.tanggal_sidang,
            pjs.jam_sidang,
            pjs.sampai_jam,
            pjs.agenda,
            pjs.urutan AS sidang_ke,
            pjs.ruangan,
            rs.nama AS ruangan_nama,
            pjs.dihadiri_oleh,
            pjs.ditunda,
            pjs.alasan_ditunda,
            pjs.sidang_keliling,
            pjs.sidang_ditempat,
            pjs.keterangan,
            GROUP_CONCAT(DISTINCT h.nama SEPARATOR "<br>") AS majelis_hakim,
            pp.panitera_nama,
            j.jurusita_nama,
            p1.nama AS nama_p,
            p1.alamat AS alamat_p,
            p2.nama AS nama_t,
            p2.alamat AS alamat_t,
            IFNULL(MAX(pp_previous.tanggal_putusan), "") AS tanggal_putusan
        ');

        $this->db->from('perkara_jadwal_sidang pjs');
        $this->db->join('perkara p', 'p.perkara_id = pjs.perkara_id', 'inner');
        $this->db->join('perkara_hakim_pn ph', 'p.perkara_id = ph.perkara_id AND ph.aktif = "Y"', 'left');
        $this->db->join('hakim_pn h', 'ph.hakim_id = h.id', 'left');
        $this->db->join('perkara_panitera_pn pp', 'p.perkara_id = pp.perkara_id AND pp.aktif = "Y"', 'left');
        $this->db->join('perkara_jurusita j', 'p.perkara_id = j.perkara_id AND j.aktif = "Y"', 'left');
        $this->db->join('ruangan_sidang rs', 'pjs.ruangan_id = rs.id', 'left');
        $this->db->join('perkara_pihak1 p1', 'p.perkara_id = p1.perkara_id', 'left');
        $this->db->join('perkara_pihak2 p2', 'p.perkara_id = p2.perkara_id', 'left');

        // Join for checking if the case has been decided (uses left join and subquery pattern to be efficient)
        $this->db->join('perkara_putusan pp_previous', 'p.perkara_id = pp_previous.perkara_id', 'left');

        // Apply filters
        if (!empty($filters)) {
            // Date range filter
            if (!empty($filters['tanggal_mulai']) && !empty($filters['tanggal_akhir'])) {
                $this->db->where('pjs.tanggal_sidang >=', $filters['tanggal_mulai']);
                $this->db->where('pjs.tanggal_sidang <=', $filters['tanggal_akhir']);
            }
            // Single date filter
            else if (!empty($filters['tanggal_sidang'])) {
                $this->db->where('pjs.tanggal_sidang', $filters['tanggal_sidang']);
            }

            // Jurusita filter - updated to use jurusita_id
            if (!empty($filters['jurusita'])) {
                $this->db->where('j.jurusita_id', $filters['jurusita']);
            }

            // Ruangan filter
            if (!empty($filters['ruangan_id'])) {
                $this->db->where('pjs.ruangan_id', $filters['ruangan_id']);
            }

            // Search filter (searches multiple fields)
            if (!empty($filters['search'])) {
                $this->db->group_start();
                $this->db->like('p.nomor_perkara', $filters['search']);
                $this->db->or_like('p1.nama', $filters['search']);
                $this->db->or_like('p2.nama', $filters['search']);
                $this->db->or_like('pjs.agenda', $filters['search']);
                $this->db->group_end();
            }

            // Status filter
            if (isset($filters['status'])) {
                if ($filters['status'] == 'pending') {
                    $this->db->where('pp_previous.tanggal_putusan IS NULL');
                } else if ($filters['status'] == 'decided') {
                    $this->db->where('pp_previous.tanggal_putusan IS NOT NULL');
                }
            }
        }

        // Group by to avoid duplicates
        $this->db->group_by('pjs.id');

        // Order by date, then time
        $this->db->order_by('pjs.tanggal_sidang', 'ASC');
        $this->db->order_by('pjs.jam_sidang', 'ASC');

        // Apply pagination if needed
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        // Get the results
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count total jadwal sidang records (for pagination)
     * 
     * @param array $filters Filter parameters
     * @return int Total count
     */
    public function count_jadwal_sidang($filters = [])
    {
        $this->db->select('COUNT(DISTINCT pjs.id) as count');
        $this->db->from('perkara_jadwal_sidang pjs');
        $this->db->join('perkara p', 'p.perkara_id = pjs.perkara_id', 'inner');
        $this->db->join('perkara_jurusita j', 'p.perkara_id = j.perkara_id AND j.aktif = "Y"', 'left');
        $this->db->join('perkara_pihak1 p1', 'p.perkara_id = p1.perkara_id', 'left');
        $this->db->join('perkara_pihak2 p2', 'p.perkara_id = p2.perkara_id', 'left');

        // Apply same filters
        if (!empty($filters)) {
            // Apply filters as in get_jadwal_sidang
            if (!empty($filters['tanggal_sidang'])) {
                $this->db->where('pjs.tanggal_sidang', $filters['tanggal_sidang']);
            }

            if (!empty($filters['jurusita'])) {
                $this->db->where('j.jurusita_nama', $filters['jurusita']);
            }

            // Additional filters here...
        }

        $query = $this->db->get();
        return $query->row()->count;
    }

    /**
     * Get detailed case information
     * 
     * @param int $perkara_id Case ID
     * @return object Case details
     */
    public function get_detail_perkara($perkara_id)
    {
        $this->db->select('
            p.*,
            GROUP_CONCAT(DISTINCT h.nama ORDER BY ph.urutan SEPARATOR "<br>") AS majelis_hakim,
            pp.panitera_nama,
            j.jurusita_nama,
            p1.nama AS nama_p, 
            p1.alamat AS alamat_p, 
            p2.nama AS nama_t, 
            p2.alamat AS alamat_t,
            ppen.majelis_hakim_text,
            ppen.panitera_pengganti_text,
            pput.tanggal_putusan,
            pput.status_putusan_nama,
            pput.amar_putusan
        ');

        $this->db->from('perkara p');
        $this->db->join('perkara_hakim_pn ph', 'p.perkara_id = ph.perkara_id AND ph.aktif = "Y"', 'left');
        $this->db->join('hakim_pn h', 'ph.hakim_id = h.id', 'left');
        $this->db->join('perkara_panitera_pn pp', 'p.perkara_id = pp.perkara_id AND pp.aktif = "Y"', 'left');
        $this->db->join('perkara_jurusita j', 'p.perkara_id = j.perkara_id AND j.aktif = "Y"', 'left');
        $this->db->join('perkara_pihak1 p1', 'p.perkara_id = p1.perkara_id', 'left');
        $this->db->join('perkara_pihak2 p2', 'p.perkara_id = p2.perkara_id', 'left');
        $this->db->join('perkara_penetapan ppen', 'p.perkara_id = ppen.perkara_id', 'left');
        $this->db->join('perkara_putusan pput', 'p.perkara_id = pput.perkara_id', 'left');

        $this->db->where('p.perkara_id', $perkara_id);
        $this->db->group_by('p.perkara_id');

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get all court session history for a case
     *
     * @param int $perkara_id Case ID
     * @return array List of court sessions
     */
    public function get_riwayat_sidang($perkara_id)
    {
        $this->db->select('
            pjs.*,
            rs.nama AS ruangan_nama,
            CASE 
                WHEN pjs.dihadiri_oleh = 1 THEN "Penggugat"
                WHEN pjs.dihadiri_oleh = 2 THEN "Tergugat"
                WHEN pjs.dihadiri_oleh = 3 THEN "Penggugat & Tergugat"
                ELSE "Tidak Ada"
            END AS kehadiran
        ');

        $this->db->from('perkara_jadwal_sidang pjs');
        $this->db->join('ruangan_sidang rs', 'pjs.ruangan_id = rs.id', 'left');

        $this->db->where('pjs.perkara_id', $perkara_id);
        $this->db->order_by('pjs.tanggal_sidang', 'ASC');
        $this->db->order_by('pjs.jam_sidang', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get statistics for dashboard
     * 
     * @param string $period day|week|month
     * @return object Statistics data
     */
    public function get_statistics($period = 'day')
    {
        // Initialize statistics object
        $stats = new stdClass();

        // Today's date
        $today = date('Y-m-d');

        // Calculate period boundaries
        if ($period == 'day') {
            $start_date = $today;
            $end_date = $today;
        } else if ($period == 'week') {
            $start_date = date('Y-m-d', strtotime('monday this week'));
            $end_date = date('Y-m-d', strtotime('sunday this week'));
        } else if ($period == 'month') {
            $start_date = date('Y-m-01');
            $end_date = date('Y-m-t');
        }

        // Total sessions in the period
        $this->db->select('COUNT(*) as total');
        $this->db->from('perkara_jadwal_sidang');
        $this->db->where('tanggal_sidang >=', $start_date);
        $this->db->where('tanggal_sidang <=', $end_date);
        $query = $this->db->get();
        $stats->total_sidang = $query->row()->total;

        // Sessions per room
        $this->db->select('rs.nama, COUNT(*) as total');
        $this->db->from('perkara_jadwal_sidang pjs');
        $this->db->join('ruangan_sidang rs', 'pjs.ruangan_id = rs.id', 'left');
        $this->db->where('pjs.tanggal_sidang >=', $start_date);
        $this->db->where('pjs.tanggal_sidang <=', $end_date);
        $this->db->group_by('pjs.ruangan_id');
        $this->db->order_by('total', 'DESC');
        $query = $this->db->get();
        $stats->per_ruangan = $query->result();

        // Sessions per case type
        $this->db->select('p.jenis_perkara_nama, COUNT(*) as total');
        $this->db->from('perkara_jadwal_sidang pjs');
        $this->db->join('perkara p', 'pjs.perkara_id = p.perkara_id', 'inner');
        $this->db->where('pjs.tanggal_sidang >=', $start_date);
        $this->db->where('pjs.tanggal_sidang <=', $end_date);
        $this->db->group_by('p.jenis_perkara_nama');
        $this->db->order_by('total', 'DESC');
        $query = $this->db->get();
        $stats->per_jenis_perkara = $query->result();

        // Attendance stats
        $this->db->select('
            SUM(CASE WHEN dihadiri_oleh = 0 THEN 1 ELSE 0 END) as tidak_hadir,
            SUM(CASE WHEN dihadiri_oleh = 1 THEN 1 ELSE 0 END) as hadir_penggugat,
            SUM(CASE WHEN dihadiri_oleh = 2 THEN 1 ELSE 0 END) as hadir_tergugat,
            SUM(CASE WHEN dihadiri_oleh = 3 THEN 1 ELSE 0 END) as hadir_keduanya
        ');
        $this->db->from('perkara_jadwal_sidang');
        $this->db->where('tanggal_sidang >=', $start_date);
        $this->db->where('tanggal_sidang <=', $end_date);
        $query = $this->db->get();
        $stats->kehadiran = $query->row();

        return $stats;
    }

    /**
     * Get all available jurusita (process servers) with detailed info
     * 
     * @return array List of jurusita
     */
    public function get_jurusita()
    {
        $this->db->select('jurusita_id, jurusita_nama as nama, aktif');
        $this->db->from('perkara_jurusita');
        $this->db->where('aktif', 'Y');
        $this->db->group_by('jurusita_id, jurusita_nama');  // Group by to avoid duplicates
        $this->db->order_by('jurusita_nama', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get all available court rooms
     * 
     * @return array List of court rooms
     */
    public function get_ruangan()
    {
        $this->db->select('id, nama');
        $this->db->from('ruangan_sidang');
        $this->db->order_by('nama', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get calendar data for a specific month
     * 
     * @param int $month Month (1-12)
     * @param int $year Year (e.g., 2023)
     * @return array Calendar data
     */
    public function get_calendar_data($month, $year)
    {
        // Calculate first and last day of the month
        $first_day = sprintf('%04d-%02d-01', $year, $month);
        $last_day = date('Y-m-t', strtotime($first_day));

        $this->db->select('
            tanggal_sidang,
            COUNT(*) as total_sidang,
            GROUP_CONCAT(DISTINCT ruangan) as ruangan_list
        ');
        $this->db->from('perkara_jadwal_sidang');
        $this->db->where('tanggal_sidang >=', $first_day);
        $this->db->where('tanggal_sidang <=', $last_day);
        $this->db->group_by('tanggal_sidang');
        $query = $this->db->get();

        // Format result as associative array with date as key
        $result = [];
        foreach ($query->result() as $row) {
            $date = $row->tanggal_sidang;
            $result[$date] = [
                'total' => $row->total_sidang,
                'rooms' => explode(',', $row->ruangan_list)
            ];
        }

        return $result;
    }
}
