<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_ecourt_monitoring extends CI_Model
{
    /**
     * Get summary statistics for E-Court cases
     * 
     * @param string $tahun Year to filter
     * @return object Summary statistics
     */
    public function get_summary($tahun)
    {
        // Total E-Court cases
        $this->db->select('COUNT(*) as total_ecourt');
        $this->db->from('perkara_efiling pe');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $result = $this->db->get()->row();

        $summary = new stdClass();
        $summary->total_ecourt = $result->total_ecourt;

        // Cases not registered yet
        $this->db->select('COUNT(*) as not_registered');
        $this->db->from('perkara_efiling pe');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pe.nomor_perkara IS NULL');
        $result = $this->db->get()->row();
        $summary->not_registered = $result->not_registered;

        // Cases registered but judge not assigned
        $this->db->select('COUNT(*) as pending_pmh');
        $this->db->from('perkara_efiling pe');
        $this->db->join('perkara p', 'pe.nomor_perkara = p.nomor_perkara', 'left');
        $this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'left');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pe.nomor_perkara IS NOT NULL');
        $this->db->where('pp.penetapan_majelis_hakim IS NULL');
        $result = $this->db->get()->row();
        $summary->pending_pmh = $result->pending_pmh;

        // Cases with judge assigned but no decision
        $this->db->select('COUNT(*) as pending_decision');
        $this->db->from('perkara_efiling pe');
        $this->db->join('perkara p', 'pe.nomor_perkara = p.nomor_perkara', 'left');
        $this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'left');
        $this->db->join('perkara_putusan pput', 'p.perkara_id = pput.perkara_id', 'left');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pp.penetapan_majelis_hakim IS NOT NULL');
        $this->db->where('pput.tanggal_putusan IS NULL');
        $result = $this->db->get()->row();
        $summary->pending_decision = $result->pending_decision;

        // Cases with decision but no document upload
        $this->db->select('COUNT(*) as pending_upload');
        $this->db->from('perkara_efiling pe');
        $this->db->join('perkara p', 'pe.nomor_perkara = p.nomor_perkara', 'left');
        $this->db->join('perkara_putusan pput', 'p.perkara_id = pput.perkara_id', 'left');
        $this->db->join('dirput_dokumen dd', 'p.perkara_id = dd.perkara_id', 'left');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pput.tanggal_putusan IS NOT NULL');
        $this->db->group_start();
        $this->db->where('dd.perkara_id IS NULL');
        $this->db->or_where('dd.link_dirput IS NULL');
        $this->db->group_end();
        $result = $this->db->get()->row();
        $summary->pending_upload = $result->pending_upload;

        // Calculate percentage completion
        if ($summary->total_ecourt > 0) {
            $completed = $summary->total_ecourt -
                ($summary->not_registered +
                    $summary->pending_pmh +
                    $summary->pending_decision +
                    $summary->pending_upload);
            $summary->completion_percentage = round(($completed / $summary->total_ecourt) * 100, 1);
        } else {
            $summary->completion_percentage = 0;
        }

        return $summary;
    }

    /**
     * Get cases that have not been registered yet
     * 
     * @param string $tahun Year to filter
     * @return array Unregistered cases
     */
    public function get_unregistered($tahun)
    {
        $this->db->select('
            pe.efiling_id,
            pe.tanggal_pendaftaran,
            pe.jenis_perkara_nama,
            pe.status_pendaftaran_text,
            pe.jumlah_skum,
            pe.status_pembayaran,
            pe.tanggal_bayar,
            DATEDIFF(NOW(), pe.tanggal_pendaftaran) as lama_tunggu
        ');
        $this->db->from('perkara_efiling pe');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pe.nomor_perkara IS NULL');
        $this->db->order_by('pe.tanggal_pendaftaran', 'ASC');
        $this->db->limit(10); // Limit to avoid overloading

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get cases pending judge assignment
     * 
     * @param string $tahun Year to filter
     * @return array Pending cases
     */
    public function get_pending_judge_assignment($tahun)
    {
        $this->db->select('
            p.perkara_id,
            p.nomor_perkara,
            p.jenis_perkara_nama,
            p.tanggal_pendaftaran,
            pe.efiling_id,
            DATEDIFF(NOW(), p.tanggal_pendaftaran) as lama_tunggu
        ');
        $this->db->from('perkara_efiling pe');
        $this->db->join('perkara p', 'pe.nomor_perkara = p.nomor_perkara', 'left');
        $this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'left');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pe.nomor_perkara IS NOT NULL');
        $this->db->where('pp.penetapan_majelis_hakim IS NULL');
        $this->db->order_by('p.tanggal_pendaftaran', 'ASC');
        $this->db->limit(10); // Limit to avoid overloading

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get cases pending decision
     * 
     * @param string $tahun Year to filter
     * @return array Pending decision cases
     */
    public function get_pending_decision($tahun)
    {
        $this->db->select('
            p.perkara_id,
            p.nomor_perkara,
            p.jenis_perkara_nama,
            p.tanggal_pendaftaran,
            pp.penetapan_majelis_hakim,
            pe.efiling_id,
            DATEDIFF(NOW(), pp.penetapan_majelis_hakim) as lama_tunggu
        ');
        $this->db->from('perkara_efiling pe');
        $this->db->join('perkara p', 'pe.nomor_perkara = p.nomor_perkara', 'left');
        $this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'left');
        $this->db->join('perkara_putusan pput', 'p.perkara_id = pput.perkara_id', 'left');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pp.penetapan_majelis_hakim IS NOT NULL');
        $this->db->where('pput.tanggal_putusan IS NULL');
        $this->db->order_by('pp.penetapan_majelis_hakim', 'ASC');
        $this->db->limit(10); // Limit to avoid overloading

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get cases pending document upload
     * 
     * @param string $tahun Year to filter
     * @return array Pending document upload cases
     */
    public function get_pending_document_upload($tahun)
    {
        $this->db->select('
            p.perkara_id,
            p.nomor_perkara,
            p.jenis_perkara_nama,
            pput.tanggal_putusan,
            pe.efiling_id,
            DATEDIFF(NOW(), pput.tanggal_putusan) as lama_tunggu
        ');
        $this->db->from('perkara_efiling pe');
        $this->db->join('perkara p', 'pe.nomor_perkara = p.nomor_perkara', 'left');
        $this->db->join('perkara_putusan pput', 'p.perkara_id = pput.perkara_id', 'left');
        $this->db->join('dirput_dokumen dd', 'p.perkara_id = dd.perkara_id', 'left');
        $this->db->where('YEAR(pe.tanggal_pendaftaran)', $tahun);
        $this->db->where('pput.tanggal_putusan IS NOT NULL');
        $this->db->group_start();
        $this->db->where('dd.perkara_id IS NULL');
        $this->db->or_where('dd.link_dirput IS NULL');
        $this->db->group_end();
        $this->db->order_by('pput.tanggal_putusan', 'ASC');
        $this->db->limit(10); // Limit to avoid overloading

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get specific case details
     * 
     * @param int $perkara_id Case ID
     * @return object Case details
     */
    public function get_case($perkara_id)
    {
        $this->db->select('
            p.*,
            pe.efiling_id,
            pe.tanggal_pendaftaran as ecourt_reg_date,
            pe.status_pembayaran,
            pe.jumlah_skum,
            pe.tanggal_bayar,
            pp.penetapan_majelis_hakim,
            pp.majelis_hakim_text,
            pp.panitera_pengganti_text,
            pp.penetapan_hari_sidang,
            pput.tanggal_putusan,
            pput.tanggal_minutasi,
            dd.created_date as dokumen_upload_date,
            dd.link_dirput
        ');
        $this->db->from('perkara p');
        $this->db->join('perkara_efiling_id pei', 'p.perkara_id = pei.perkara_id', 'left');
        $this->db->join('perkara_efiling pe', 'pe.efiling_id = pei.efiling_id OR pe.nomor_perkara = p.nomor_perkara', 'left');
        $this->db->join('perkara_penetapan pp', 'p.perkara_id = pp.perkara_id', 'left');
        $this->db->join('perkara_putusan pput', 'p.perkara_id = pput.perkara_id', 'left');
        $this->db->join('dirput_dokumen dd', 'p.perkara_id = dd.perkara_id', 'left');
        $this->db->where('p.perkara_id', $perkara_id);

        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get case timeline
     * 
     * @param int $perkara_id Case ID
     * @return array Timeline events
     */
    public function get_timeline($perkara_id)
    {
        $timeline = [];

        // Get basic case information
        $case = $this->get_case($perkara_id);

        if (!$case) {
            return $timeline;
        }

        // E-Court registration
        if ($case->ecourt_reg_date) {
            $timeline[] = [
                'date' => $case->ecourt_reg_date,
                'event' => 'Pendaftaran E-Court',
                'description' => 'Perkara didaftarkan melalui aplikasi E-Court',
                'icon' => 'fa-file-upload',
                'color' => 'bg-info'
            ];
        }

        // Payment
        if ($case->tanggal_bayar) {
            $timeline[] = [
                'date' => $case->tanggal_bayar,
                'event' => 'Pembayaran PNBP',
                'description' => 'Biaya perkara sebesar Rp ' . number_format($case->jumlah_skum, 0, ',', '.') . ' telah dibayar',
                'icon' => 'fa-money-bill-wave',
                'color' => 'bg-success'
            ];
        }

        // Case registration
        if ($case->tanggal_pendaftaran) {
            $timeline[] = [
                'date' => $case->tanggal_pendaftaran,
                'event' => 'Registrasi Perkara',
                'description' => 'Perkara teregistrasi dengan nomor ' . $case->nomor_perkara,
                'icon' => 'fa-clipboard-check',
                'color' => 'bg-primary'
            ];
        }

        // Judge assignment
        if ($case->penetapan_majelis_hakim) {
            $timeline[] = [
                'date' => $case->penetapan_majelis_hakim,
                'event' => 'Penetapan Majelis Hakim',
                'description' => 'Majelis hakim ditetapkan',
                'icon' => 'fa-gavel',
                'color' => 'bg-warning'
            ];
        }

        // First hearing date
        if ($case->penetapan_hari_sidang) {
            $timeline[] = [
                'date' => $case->penetapan_hari_sidang,
                'event' => 'Penetapan Hari Sidang',
                'description' => 'Jadwal sidang pertama ditetapkan',
                'icon' => 'fa-calendar',
                'color' => 'bg-info'
            ];
        }

        // Decision
        if ($case->tanggal_putusan) {
            $timeline[] = [
                'date' => $case->tanggal_putusan,
                'event' => 'Putusan',
                'description' => 'Perkara telah diputus',
                'icon' => 'fa-balance-scale',
                'color' => 'bg-danger'
            ];
        }

        // Minutasi
        if ($case->tanggal_minutasi) {
            $timeline[] = [
                'date' => $case->tanggal_minutasi,
                'event' => 'Minutasi',
                'description' => 'Berkas putusan telah diminutasi',
                'icon' => 'fa-file-signature',
                'color' => 'bg-primary'
            ];
        }

        // Document upload
        if ($case->dokumen_upload_date) {
            $description = $case->link_dirput
                ? 'Dokumen telah dipublikasikan'
                : 'Dokumen diunggah namun belum dipublikasikan';

            $timeline[] = [
                'date' => $case->dokumen_upload_date,
                'event' => 'Upload Dokumen',
                'description' => $description,
                'icon' => 'fa-cloud-upload-alt',
                'color' => 'bg-success'
            ];
        }

        // Sort timeline by date
        usort($timeline, function ($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });

        return $timeline;
    }
}
