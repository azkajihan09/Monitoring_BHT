<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usia_pihak_p extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("M_usia_pihak_p");
    }

   public function index()
    {
        $jenis_kelamin = $this->input->post('jenis_kelamin');
        $lap_bulan = $this->input->post('lap_bulan');
        $lap_tahun = $this->input->post('lap_tahun');
        $data['datafilter'] = $this->M_usia_pihak_p->Usia_pihak_p($jenis_kelamin, $lap_bulan, $lap_tahun);
        $this->load->view('template/new_header');
        $this->load->view('template/new_sidebar');
        $this->load->view('v_usia_pihak_p', $data);
        $this->load->view('template/new_footer');   
    }

 /*   public function excel()
    {
        $data['datafilter'] = $this->M_usia_pihak_p->Usia_pihak_p($jenis_kelamin, $lap_bulan, $lap_tahun);
        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel.php');
        require(APPPATH. 'PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

        $object = new PHPExcel();

        $object->getProperties()->setCreator("LAB SIPP");
        $object->getProperties()->setLastModifiedBy("LAB SIPP");
        $object->getProperties()->setTitle("Usia Pihak P");

        $object->setActiveSheetIndex(0);

        $object->getActiveSheet()->setCellValue('A1', 'Nomor');
        $object->getActiveSheet()->setCellValue('B1', 'Nomor Perkara');
        $object->getActiveSheet()->setCellValue('C1', 'Tanggal Pendaftaran');
        $object->getActiveSheet()->setCellValue('D1', 'Nama Pihak');
        $object->getActiveSheet()->setCellValue('E1', 'Tanggal Lahir');
        $object->getActiveSheet()->setCellValue('F1', 'Usia');
        $object->getActiveSheet()->setCellValue('G1', 'Jenis Kelamin');

        $baris = 2;
        $no = 1;

        foreach ($data['datafilter'] as $row)
        {
            $object->getActiveSheet()->setCellValue('A'.$baris, $no++);
            $object->getActiveSheet()->setCellValue('A'.$baris, $row->nomor_perkara);
            $object->getActiveSheet()->setCellValue('A'.$baris, $row->tanggal_pendaftaran);
            $object->getActiveSheet()->setCellValue('A'.$baris, $row->nama);
            $object->getActiveSheet()->setCellValue('A'.$baris, $row->tanggal_lahir);
            $object->getActiveSheet()->setCellValue('A'.$baris, $row->usia);
            $object->getActiveSheet()->setCellValue('A'.$baris, $row->jenis_kelamin);

            $baris++;
        }

        $filename="data_usia_p".'.xlsx';

        $object->getActiveSheet()->setTitle("Data Usia P");

        header('content-type: application/vnd.openxmlformats-spreadsheetml.sheet');
        header('content-Disposition: attachment;filename"'.$filename.'"');
        header('Cache-Control: max-age=0');

        $Writer=PHPExcel_IOFactory::createwriter($object, 'Excel2007');
        $writer->save('php://output');

        exit;
    }*/
}
