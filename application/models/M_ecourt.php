<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_ecourt extends CI_Model
{
	/**
	 * Get E-Court cases based on filters
	 * 
	 * @param string $jenis_perkara Type of case
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return array List of E-Court cases
	 */
	public function ecourt($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Security: use query binding to prevent SQL injection
		// Use proper SQL for the CASE WHEN statement
		$this->db->select('
            pe.efiling_id,
            p.perkara_id,
            pp1.nama as nama_pihak,
            ph.email,
            p.jenis_perkara_nama,
            p.nomor_perkara,
            pe.tanggal_pendaftaran,
            CASE WHEN p.nomor_perkara IS NOT NULL THEN "Teregistrasi" ELSE "Pendaftaran" END as status
        ', FALSE); // Important: FALSE parameter disables escaping for raw SQL

		$this->db->from('perkara_efiling pe');
		$this->db->join('perkara_efiling_id pei', 'pe.efiling_id = pei.efiling_id', 'left');
		$this->db->join('perkara p', 'p.perkara_id = pei.perkara_id OR p.nomor_perkara = pe.nomor_perkara', 'left');
		$this->db->join('perkara_pihak1 pp1', 'p.perkara_id = pp1.perkara_id AND pp1.urutan = 1', 'left');
		$this->db->join('pihak ph', 'pp1.pihak_id = ph.id', 'left');

		// Apply filters
		$this->db->where('YEAR(pe.tanggal_pendaftaran)', $lap_tahun);
		$this->db->where('MONTH(pe.tanggal_pendaftaran)', $lap_bulan);

		if ($jenis_perkara !== 'all') {
			$this->db->like('p.nomor_perkara', $jenis_perkara, 'both');
		}

		$this->db->order_by('pe.tanggal_pendaftaran', 'DESC');

		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Get statistics for E-Court cases
	 * 
	 * @param string $lap_bulan Month (01-12)
	 * @param string $lap_tahun Year
	 * @return object Statistics data
	 */
	public function get_stats($jenis_perkara, $lap_bulan, $lap_tahun)
	{
		// Count registered cases
		$this->db->select('COUNT(*) as registered_count');
		$this->db->from('perkara_efiling pe');
		$this->db->join('perkara_efiling_id pei', 'pe.efiling_id = pei.efiling_id', 'left');
		$this->db->join('perkara p', 'p.perkara_id = pei.perkara_id OR p.nomor_perkara = pe.nomor_perkara', 'left');
		$this->db->where('YEAR(pe.tanggal_pendaftaran)', $lap_tahun);
		$this->db->where('MONTH(pe.tanggal_pendaftaran)', $lap_bulan);
		$this->db->where('p.nomor_perkara IS NOT NULL');

		$result = $this->db->get()->row();
		$stats = new stdClass();
		$stats->registered_count = $result->registered_count;

		// Count Gugatan (Pdt.G) cases
		$this->db->select('COUNT(*) as gugatan_count');
		$this->db->from('perkara_efiling pe');
		$this->db->join('perkara_efiling_id pei', 'pe.efiling_id = pei.efiling_id', 'left');
		$this->db->join('perkara p', 'p.perkara_id = pei.perkara_id OR p.nomor_perkara = pe.nomor_perkara', 'left');
		$this->db->where('YEAR(pe.tanggal_pendaftaran)', $lap_tahun);
		$this->db->where('MONTH(pe.tanggal_pendaftaran)', $lap_bulan);
		$this->db->like('p.nomor_perkara', 'Pdt.G', 'both');

		$result = $this->db->get()->row();
		$stats->gugatan_count = $result->gugatan_count;

		// Count Permohonan (Pdt.P) cases
		$this->db->select('COUNT(*) as permohonan_count');
		$this->db->from('perkara_efiling pe');
		$this->db->join('perkara_efiling_id pei', 'pe.efiling_id = pei.efiling_id', 'left');
		$this->db->join('perkara p', 'p.perkara_id = pei.perkara_id OR p.nomor_perkara = pe.nomor_perkara', 'left');
		$this->db->where('YEAR(pe.tanggal_pendaftaran)', $lap_tahun);
		$this->db->where('MONTH(pe.tanggal_pendaftaran)', $lap_bulan);
		$this->db->like('p.nomor_perkara', 'Pdt.P', 'both');

		$result = $this->db->get()->row();
		$stats->permohonan_count = $result->permohonan_count;

		return $stats;
	}

	/**
	 * Get case detail
	 * 
	 * @param int $perkara_id Case ID
	 * @return object Case details
	 */
	public function get_case_detail($perkara_id)
	{
		$this->db->select('
            p.*,
            pe.efiling_id,
            pe.tanggal_pendaftaran as ecourt_reg_date,
            pe.status_pembayaran,
            pe.jumlah_skum,
            pe.tanggal_bayar,
            pp1.nama as nama_penggugat,
            pp2.nama as nama_tergugat
        ');
		$this->db->from('perkara p');
		$this->db->join('perkara_efiling_id pei', 'p.perkara_id = pei.perkara_id', 'left');
		$this->db->join('perkara_efiling pe', 'pe.efiling_id = pei.efiling_id', 'left');
		$this->db->join('perkara_pihak1 pp1', 'p.perkara_id = pp1.perkara_id AND pp1.urutan = 1', 'left');
		$this->db->join('perkara_pihak2 pp2', 'p.perkara_id = pp2.perkara_id AND pp2.urutan = 1', 'left');
		$this->db->where('p.perkara_id', $perkara_id);

		$query = $this->db->get();
		return $query->row();
	}
}
