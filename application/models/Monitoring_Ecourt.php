<?php

/**
 * Model for handling E-Court monitoring data
 */
require_once 'config/timezone.php';
class MonitoringEcourt
{
    /**
     * Get all E-Court cases for a specific year
     * 
     * @param resource $conn Database connection
     * @param int $tahun Year to filter
     * @return array E-Court cases
     */
    public static function getEcourtCases($conn, $tahun)
    {
        $query = "SELECT A.*, B.proses_terakhir_text, B.perkara_id,
                    B.tanggal_pendaftaran AS tanggal_register,
                    B.jenis_perkara_text,
                    DATEDIFF(NOW(), A.tanggal_pendaftaran) AS lama_proses,
                    CASE WHEN B.nomor_perkara IS NULL THEN 'Belum Teregister' ELSE 'Teregister' END AS status_register
                FROM perkara_efiling A
                LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                WHERE YEAR(A.tanggal_pendaftaran) = '$tahun'
                ORDER BY A.efiling_id DESC";
        
        $result = mysqli_query($conn, $query);
        if (!$result) {
            error_log("SQL Error in getEcourtCases: " . mysqli_error($conn));
            return array();
        }
        
        $cases = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $cases[] = $row;
        }
        
        return $cases;
    }
    
    /**
     * Get E-Court cases that have not been registered with a case number
     * 
     * @param resource $conn Database connection
     * @param int $tahun Year to filter
     * @return array Unregistered E-Court cases
     */
    public static function getUnregisteredEcourtCases($conn, $tahun)
    {
        $query = "SELECT A.*, 
                DATEDIFF(NOW(), A.tanggal_pendaftaran) AS lama_tunggu
                FROM perkara_efiling A
                LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                WHERE YEAR(A.tanggal_pendaftaran) = '$tahun' AND A.nomor_perkara IS NULL
                ORDER BY A.tanggal_pendaftaran ASC";
        
        $result = mysqli_query($conn, $query);
        if (!$result) {
            error_log("SQL Error in getUnregisteredEcourtCases: " . mysqli_error($conn));
            return array();
        }
        
        $cases = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $cases[] = $row;
        }
        
        return $cases;
    }
    
    /**
     * Get E-Court cases that have been registered but pending judge assignment
     * 
     * @param resource $conn Database connection
     * @param int $tahun Year to filter
     * @return array Registered E-Court cases pending judge assignment
     */
    public static function getPendingJudgeAssignmentCases($conn, $tahun)
    {
        $query = "SELECT A.*, B.perkara_id, B.proses_terakhir_text,
                B.tanggal_pendaftaran AS tanggal_register,
                B.jenis_perkara_text, B.para_pihak,
                DATEDIFF(NOW(), B.tanggal_pendaftaran) AS lama_pending,
                C.majelis_hakim_text, C.penetapan_majelis_hakim
                FROM perkara_efiling A
                LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                LEFT JOIN perkara_penetapan C ON C.perkara_id = B.perkara_id
                WHERE YEAR(A.tanggal_pendaftaran) = '$tahun' 
                AND A.nomor_perkara IS NOT NULL
                AND C.penetapan_majelis_hakim IS NULL
                ORDER BY B.tanggal_pendaftaran ASC";
        
        $result = mysqli_query($conn, $query);
        if (!$result) {
            error_log("SQL Error in getPendingJudgeAssignmentCases: " . mysqli_error($conn));
            return array();
        }
        
        $cases = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $cases[] = $row;
        }
        
        return $cases;
    }
    
    /**
     * Get E-Court cases with judge assignment but pending decision
     * 
     * @param resource $conn Database connection
     * @param int $tahun Year to filter
     * @return array Cases pending decision
     */
    public static function getPendingDecisionCases($conn, $tahun)
    {
        $query = "SELECT A.*, B.perkara_id, B.proses_terakhir_text,
                B.tanggal_pendaftaran AS tanggal_register,
                B.jenis_perkara_text, B.para_pihak,
                C.majelis_hakim_text, C.penetapan_majelis_hakim,
                DATEDIFF(NOW(), C.penetapan_majelis_hakim) AS lama_sejak_pmh,
                B.tanggal_putusan
                FROM perkara_efiling A
                LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                LEFT JOIN perkara_penetapan C ON C.perkara_id = B.perkara_id
                WHERE YEAR(A.tanggal_pendaftaran) = '$tahun' 
                AND C.penetapan_majelis_hakim IS NOT NULL
                AND B.tanggal_putusan IS NULL
                ORDER BY C.penetapan_majelis_hakim ASC";
        
        $result = mysqli_query($conn, $query);
        if (!$result) {
            error_log("SQL Error in getPendingDecisionCases: " . mysqli_error($conn));
            return array();
        }
        
        $cases = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $cases[] = $row;
        }
        
        return $cases;
    }
    
    /**
     * Get E-Court cases with decision but lacking uploaded documents
     * 
     * @param resource $conn Database connection
     * @param int $tahun Year to filter
     * @return array Cases pending document upload
     */
    public static function getPendingDocumentCases($conn, $tahun)
    {
        $query = "SELECT B.nomor_perkara, B.jenis_perkara_text,
                B.tanggal_pendaftaran, B.tanggal_putusan,  
                B.para_pihak, B.perkara_id, B.status_putusan_text,
                C.amar_putusan_dok, C.amar_putusan_anonimisasi_dok,
                DATEDIFF(NOW(), B.tanggal_putusan) AS lama_sejak_putusan
                FROM perkara_efiling A
                LEFT JOIN v_perkara B ON A.nomor_perkara = B.nomor_perkara 
                LEFT JOIN perkara_putusan C ON B.perkara_id = C.perkara_id
                LEFT JOIN dirput_dokumen D ON B.perkara_id = D.perkara_id
                WHERE YEAR(A.tanggal_pendaftaran) = '$tahun'
                AND B.tanggal_putusan IS NOT NULL
                AND (D.perkara_id IS NULL OR D.link_dirput IS NULL)
                ORDER BY B.tanggal_putusan ASC";
        
        $result = mysqli_query($conn, $query);
        if (!$result) {
            error_log("SQL Error in getPendingDocumentCases: " . mysqli_error($conn));
            return array();
        }
        
        $cases = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $cases[] = $row;
        }
        
        return $cases;
    }
    
    /**
     * Get summary statistics for E-Court monitoring
     * 
     * @param resource $conn Database connection
     * @param int $tahun Year to filter
     * @return array Summary statistics
     */
    public static function getEcourtSummary($conn, $tahun)
    {
        $query = "SELECT 
                (SELECT COUNT(*) FROM perkara_efiling WHERE YEAR(tanggal_pendaftaran) = '$tahun') AS total_ecourt,
                (SELECT COUNT(*) FROM perkara_efiling A LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara 
                    WHERE YEAR(A.tanggal_pendaftaran) = '$tahun' AND B.nomor_perkara IS NULL) AS belum_register,
                (SELECT COUNT(*) FROM perkara_efiling A 
                    LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                    LEFT JOIN perkara_penetapan C ON C.perkara_id = B.perkara_id
                    WHERE YEAR(A.tanggal_pendaftaran) = '$tahun' AND A.nomor_perkara IS NOT NULL 
                    AND C.penetapan_majelis_hakim IS NULL) AS belum_pmh,
                (SELECT COUNT(*) FROM perkara_efiling A 
                    LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                    LEFT JOIN perkara_penetapan C ON C.perkara_id = B.perkara_id
                    WHERE YEAR(A.tanggal_pendaftaran) = '$tahun' AND C.penetapan_majelis_hakim IS NOT NULL
                    AND B.tanggal_putusan IS NULL) AS belum_putus,
                (SELECT COUNT(*) FROM perkara_efiling A 
                    LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                    LEFT JOIN dirput_dokumen D ON B.perkara_id = D.perkara_id
                    WHERE YEAR(A.tanggal_pendaftaran) = '$tahun' AND B.tanggal_putusan IS NOT NULL
                    AND (D.perkara_id IS NULL OR D.link_dirput IS NULL)) AS belum_upload";
        
        $result = mysqli_query($conn, $query);
        if (!$result) {
            error_log("SQL Error in getEcourtSummary: " . mysqli_error($conn));
            return array(
                'total_ecourt' => 0,
                'belum_register' => 0,
                'belum_pmh' => 0,
                'belum_putus' => 0,
                'belum_upload' => 0
            );
        }
        
        return mysqli_fetch_assoc($result);
    }
    
    /**
     * Get case timeline for an e-court case
     * 
     * @param resource $conn Database connection
     * @param int $perkaraId Case ID
     * @return array Timeline events for the case
     */
    public static function getEcourtCaseTimeline($conn, $perkaraId)
    {
        $timeline = array();
        
        // Get basic case information
        $query = "SELECT A.*, B.nomor_perkara, B.jenis_perkara_text, B.para_pihak, B.tanggal_pendaftaran
                FROM perkara_efiling A
                LEFT JOIN v_perkara B ON B.nomor_perkara = A.nomor_perkara
                WHERE B.perkara_id = '$perkaraId'";
        
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $caseInfo = mysqli_fetch_assoc($result);
            
            // Add e-filing submission to timeline
            $timeline[] = array(
                'date' => $caseInfo['tanggal_pendaftaran'],
                'event' => 'Pendaftaran E-Court',
                'description' => 'Perkara didaftarkan melalui sistem E-Court',
                'icon' => 'fa-file-upload'
            );
            
            // Add case registration to timeline if registered
            if ($caseInfo['nomor_perkara']) {
                $timeline[] = array(
                    'date' => $caseInfo['tanggal_pendaftaran'],
                    'event' => 'Registrasi Perkara',
                    'description' => 'Perkara teregistrasi dengan nomor ' . $caseInfo['nomor_perkara'],
                    'icon' => 'fa-clipboard-check'
                );
            }
        }
        
        // Get judge assignment
        $query = "SELECT penetapan_majelis_hakim, majelis_hakim_text 
                FROM perkara_penetapan 
                WHERE perkara_id = '$perkaraId'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $judgeInfo = mysqli_fetch_assoc($result);
            if ($judgeInfo['penetapan_majelis_hakim']) {
                $timeline[] = array(
                    'date' => $judgeInfo['penetapan_majelis_hakim'],
                    'event' => 'Penetapan Majelis Hakim',
                    'description' => 'Majelis Hakim: ' . strip_tags($judgeInfo['majelis_hakim_text']),
                    'icon' => 'fa-gavel'
                );
            }
        }
        
        // Get first hearing date
        $query = "SELECT MIN(tanggal_sidang) as sidang_pertama
                FROM perkara_jadwal_sidang
                WHERE perkara_id = '$perkaraId'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $hearingInfo = mysqli_fetch_assoc($result);
            if ($hearingInfo['sidang_pertama']) {
                $timeline[] = array(
                    'date' => $hearingInfo['sidang_pertama'],
                    'event' => 'Sidang Pertama',
                    'description' => 'Jadwal sidang pertama',
                    'icon' => 'fa-calendar'
                );
            }
        }
        
        // Get decision information
        $query = "SELECT tanggal_putusan, tanggal_minutasi, amar_putusan
                FROM perkara_putusan
                WHERE perkara_id = '$perkaraId'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $decisionInfo = mysqli_fetch_assoc($result);
            if ($decisionInfo['tanggal_putusan']) {
                $timeline[] = array(
                    'date' => $decisionInfo['tanggal_putusan'],
                    'event' => 'Putusan',
                    'description' => 'Perkara diputus',
                    'icon' => 'fa-legal'
                );
            }
            if ($decisionInfo['tanggal_minutasi']) {
                $timeline[] = array(
                    'date' => $decisionInfo['tanggal_minutasi'],
                    'event' => 'Minutasi',
                    'description' => 'Minutasi putusan selesai',
                    'icon' => 'fa-file-signature'
                );
            }
        }
        
        // Get document upload status
        $query = "SELECT created_date, link_dirput
                FROM dirput_dokumen
                WHERE perkara_id = '$perkaraId'";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $docInfo = mysqli_fetch_assoc($result);
            if ($docInfo['created_date']) {
                $timeline[] = array(
                    'date' => $docInfo['created_date'],
                    'event' => 'Upload Dokumen',
                    'description' => $docInfo['link_dirput'] ? 'Dokumen berhasil diupload dan dipublikasikan' : 'Dokumen diupload namun belum dipublikasikan',
                    'icon' => 'fa-cloud-upload-alt'
                );
            }
        }
        
        // Sort timeline by date
        usort($timeline, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        
        return $timeline;
    }
}