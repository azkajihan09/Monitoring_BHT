<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_perkara extends CI_Model
{
	/**
	 * Get detailed information about a specific case
	 * 
	 * @param int $perkara_id Case ID
	 * @return object Case details
	 */
	public function get_detail($perkara_id)
	{
		// Build query to get case details
		$this->db->select('
            p.perkara_id,
            p.nomor_perkara,
            p.jenis_perkara_nama,
            p.tanggal_pendaftaran,
            pp.tanggal_putusan,
            pp.tanggal_minutasi,
            pp.amar_putusan,
            pp.status_putusan_nama as status_perkara,
            pac.nomor_akta_cerai,
            pac.tgl_akta_cerai,
            pac.jenis_cerai,
            a.nama as nama_p,
            b.nama as nama_t,
            pdp.tgl_nikah
        ');

		$this->db->from('perkara p');
		$this->db->join('perkara_putusan pp', 'p.perkara_id = pp.perkara_id', 'left');
		$this->db->join('perkara_akta_cerai pac', 'p.perkara_id = pac.perkara_id', 'left');
		$this->db->join('perkara_pihak1 a', 'p.perkara_id = a.perkara_id AND a.urutan = 1', 'left');
		$this->db->join('perkara_pihak2 b', 'p.perkara_id = b.perkara_id AND b.urutan = 1', 'left');
		$this->db->join('perkara_data_pernikahan pdp', 'p.perkara_id = pdp.perkara_id', 'left');

		$this->db->where('p.perkara_id', $perkara_id);

		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * Get timeline events for a case
	 * 
	 * @param int $perkara_id Case ID
	 * @return array Array of timeline events
	 */
	public function get_timeline($perkara_id)
	{
		// Create timeline events from various data points
		$timeline = [];

		// Get basic case information
		$perkara = $this->get_detail($perkara_id);

		if ($perkara) {
			// Registration event
			$timeline[] = (object)[
				'tanggal' => $perkara->tanggal_pendaftaran,
				'jenis_event' => 'pendaftaran',
				'judul' => 'Pendaftaran Perkara',
				'deskripsi' => "Perkara {$perkara->nomor_perkara} didaftarkan ke pengadilan.",
				'keterangan' => "Jenis perkara: {$perkara->jenis_perkara_nama}"
			];

			// Get judge appointment info
			$penetapan = $this->get_penetapan($perkara_id);
			if ($penetapan) {
				$timeline[] = (object)[
					'tanggal' => $penetapan->penetapan_majelis_hakim,
					'jenis_event' => 'penetapan_hakim',
					'judul' => 'Penetapan Majelis Hakim',
					'deskripsi' => "Penetapan majelis hakim untuk perkara ini.",
					'keterangan' => "Majelis: {$penetapan->majelis_hakim_nama}"
				];
			}

			// Get hearing schedules
			$jadwal_sidang = $this->get_jadwal_sidang($perkara_id);
			if ($jadwal_sidang) {
				foreach ($jadwal_sidang as $sidang) {
					$timeline[] = (object)[
						'tanggal' => $sidang->tanggal_sidang . ' ' . $sidang->jam_sidang,
						'jenis_event' => 'sidang',
						'judul' => 'Sidang: ' . $sidang->agenda,
						'deskripsi' => "Sidang dengan agenda: {$sidang->agenda}",
						'keterangan' => "Ruangan: {$sidang->ruangan}, Status: {$sidang->status_sidang}"
					];
				}
			}

			// Decision event
			if (!empty($perkara->tanggal_putusan)) {
				$timeline[] = (object)[
					'tanggal' => $perkara->tanggal_putusan,
					'jenis_event' => 'putusan',
					'judul' => 'Putusan',
					'deskripsi' => "Perkara diputus oleh majelis hakim.",
					'keterangan' => !empty($perkara->amar_putusan) ? substr($perkara->amar_putusan, 0, 200) . '...' : ''
				];
			}

			// Minutasi event
			if (!empty($perkara->tanggal_minutasi)) {
				$timeline[] = (object)[
					'tanggal' => $perkara->tanggal_minutasi,
					'jenis_event' => 'minutasi',
					'judul' => 'Minutasi',
					'deskripsi' => "Proses minutasi berkas perkara selesai."
				];
			}

			// Akta Cerai event (for divorce cases)
			if (!empty($perkara->tgl_akta_cerai)) {
				$timeline[] = (object)[
					'tanggal' => $perkara->tgl_akta_cerai,
					'jenis_event' => 'akta_cerai',
					'judul' => 'Akta Cerai',
					'deskripsi' => "Akta cerai nomor {$perkara->nomor_akta_cerai} diterbitkan.",
					'keterangan' => "Jenis cerai: {$perkara->jenis_cerai}"
				];
			}

			// Check for publication
			$publikasi = $this->get_publikasi($perkara_id);
			if ($publikasi) {
				$timeline[] = (object)[
					'tanggal' => $publikasi->created_date,
					'jenis_event' => 'publikasi',
					'judul' => 'Publikasi Putusan',
					'deskripsi' => "Putusan dipublikasikan di Direktori Putusan.",
					'link' => $publikasi->link_dirput,
					'keterangan' => "File: {$publikasi->filename}"
				];
			}

			// Sort timeline by date
			usort($timeline, function ($a, $b) {
				return strtotime($a->tanggal) - strtotime($b->tanggal);
			});
		}

		return $timeline;
	}

	/**
	 * Get judge appointment data
	 */
	private function get_penetapan($perkara_id)
	{
		$this->db->select('penetapan_majelis_hakim, majelis_hakim_nama');
		$this->db->from('perkara_penetapan');
		$this->db->where('perkara_id', $perkara_id);
		$query = $this->db->get();
		return $query->row();
	}

	/**
	 * Get hearing schedule data
	 */
	private function get_jadwal_sidang($perkara_id)
	{
		try {
			// Check if ruangan table exists
			$ruangan_exists = $this->db->table_exists('ruangan');

			// Build the appropriate SQL query based on whether the ruangan table exists
			if ($ruangan_exists) {
				$this->db->select('
                    js.urutan,
                    js.tanggal_sidang,
                    js.jam_sidang,
                    js.agenda,
                    r.nama AS ruangan,
                    CASE 
                        WHEN js.ditunda = "Y" THEN "Tunda"
                        WHEN js.dihadiri_oleh IS NOT NULL THEN "Selesai"
                        ELSE "Jadwal" 
                    END AS status_sidang
                ');

				$this->db->from('perkara_jadwal_sidang js');
				$this->db->join('ruangan r', 'js.ruangan_id = r.id', 'left');
			} else {
				$this->db->select('
                    js.urutan,
                    js.tanggal_sidang,
                    js.jam_sidang,
                    js.agenda,
                    CONCAT("Ruangan ", js.ruangan_id) AS ruangan,
                    CASE 
                        WHEN js.ditunda = "Y" THEN "Tunda"
                        WHEN js.dihadiri_oleh IS NOT NULL THEN "Selesai"
                        ELSE "Jadwal" 
                    END AS status_sidang
                ', FALSE);

				$this->db->from('perkara_jadwal_sidang js');
			}

			$this->db->where('js.perkara_id', $perkara_id);
			$this->db->order_by('js.tanggal_sidang', 'ASC');

			$query = $this->db->get();
			return $query->result();
		} catch (Exception $e) {
			// Fallback query if there are any issues
			$this->db->select('
                urutan,
                tanggal_sidang,
                jam_sidang,
                agenda,
                "Tidak diketahui" AS ruangan,
                "Tidak diketahui" AS status_sidang
            ');
			$this->db->from('perkara_jadwal_sidang');
			$this->db->where('perkara_id', $perkara_id);
			$this->db->order_by('tanggal_sidang', 'ASC');

			$query = $this->db->get();
			return $query->result();
		}
	}

	/**
	 * Get publication data
	 */
	private function get_publikasi($perkara_id)
	{
		// Check if dirput_dokumen table exists and has necessary columns
		if ($this->db->table_exists('dirput_dokumen')) {
			try {
				$this->db->select('filename, created_date, link_dirput');
				$this->db->from('dirput_dokumen');
				$this->db->where('perkara_id', $perkara_id);
				$this->db->order_by('created_date', 'DESC');
				$this->db->limit(1);
				$query = $this->db->get();
				return $query->row();
			} catch (Exception $e) {
				return null;
			}
		}
		return null;
	}
}
