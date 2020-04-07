<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HasilUjian extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}
		
		$this->load->library(['datatables']);// Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->load->model('Ujian_model', 'ujian');
		
		$this->user = $this->ion_auth->user()->row();
	}

	public function output_json($data, $encode = true)
	{
		if($encode) $data = json_encode($data);
		$this->output->set_content_type('application/json')->set_output($data);
	}

	public function data()
	{
		$nip_guru = null;
		
		if( $this->ion_auth->in_group('guru') ) {
			$nip_guru = $this->user->username;
		}

		$this->output_json($this->ujian->getHasilUjian($nip_guru), false);
	}

	public function NilaiMhs($id)
	{
		$this->output_json($this->ujian->HslUjianById($id, true), false);
	}

	public function index()
	{
		$data = [
			'user' => $this->user,
			'judul'	=> 'Ujian',
			'subjudul'=> 'Hasil Ujian',
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}
	
	public function detail($id)
	{
		$ujian = $this->ujian->getUjianById($id);
		$nilai = $this->ujian->bandingNilai($id);

		$data = [
			'user' => $this->user,
			'judul'	=> 'Ujian',
			'subjudul'=> 'Detail Hasil Ujian',
			'ujian'	=> $ujian,
			'nilai'	=> $nilai
		];

		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('ujian/detail_hasil');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function cetak($id)
	{
		$this->load->library('Pdf');

		$mhs 	= $this->ujian->getIdSiswa($this->user->username);
		$hasil 	= $this->ujian->HslUjian($id, $mhs->id_siswa)->row();
		$ujian 	= $this->ujian->getUjianById($id);
		
		$master_soal_query = $this->ujian->listPertanyaan(explode(",", $hasil->list_soal));

		$list_jawaban = explode(",", $hasil->list_jawaban);
		$master_soal = array();

		foreach ($master_soal_query as $key => $value) {
			$master_soal[$value->id_soal]['soal'] = $value->soal;
			$jawaban = "";

			foreach ($list_jawaban as $k => $val) {
				$temp = explode(":", $val);
				if ($temp[0] == $value->id_soal) {
					$jawaban = $temp[1];

					switch ($temp[1]) {
						case 'A':
						$jawaban.= ". " . $value->opsi_a;
						break;
						
						case 'B':
						$jawaban.= ". " . $value->opsi_b;
						break;
						
						case 'C':
						$jawaban.= ". " . $value->opsi_c;
						break;
						
						case 'D':
						$jawaban.= ". " . $value->opsi_d;
						break;
						
						case 'E':
						$jawaban.= ". " . $value->opsi_e;
						break;
						
						default:
							# code...
						break;
					}

				}
			}

			$master_soal[$value->id_soal]['jawaban'] = $jawaban == "" ? "" : $jawaban;
			if ($jawaban == $value->jawaban) {
				$master_soal[$value->id_soal]['status'] = "Benar";
			}else{
				$master_soal[$value->id_soal]['status'] = "Salah";
			}
		}

		$hasil->master_soal = $master_soal;

		$data = [
			'ujian' => $ujian,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];

		// echo "<pre>";
		// print_r($data);

		// die();
		
		$this->load->view('ujian/cetak', $data);
	}

	public function cetak_list($id)
	{
		$this->load->library('Pdf');
		// echo "<pre>";
		$hasil 	= $this->ujian->HslUjianByIdUjian($id)->row();
		// print_r($hasil);
		// die;
		$mhs 	= $this->ujian->getIdSiswaById($hasil->siswa_id);
		$ujian 	= $this->ujian->getUjianById($hasil->ujian_id);
		
		$master_soal_query = $this->ujian->listPertanyaan(explode(",", $hasil->list_soal));

		$list_jawaban = explode(",", $hasil->list_jawaban);
		$master_soal = array();

		foreach ($master_soal_query as $key => $value) {
			$master_soal[$value->id_soal]['soal'] = $value->soal;
			$jawaban = "";
			$jawabanField = "";

			foreach ($list_jawaban as $k => $val) {
				$temp = explode(":", $val);
				if ($temp[0] == $value->id_soal) {
					$jawaban = $temp[1];
					$jawabanField = $temp[1];

					switch ($temp[1]) {
						case 'A':
						$jawaban.= ". " . $value->opsi_a;
						break;
						
						case 'B':
						$jawaban.= ". " . $value->opsi_b;
						break;
						
						case 'C':
						$jawaban.= ". " . $value->opsi_c;
						break;
						
						case 'D':
						$jawaban.= ". " . $value->opsi_d;
						break;
						
						case 'E':
						$jawaban.= ". " . $value->opsi_e;
						break;
						
						default:
							# code...
						break;
					}

				}
			}

			$master_soal[$value->id_soal]['jawaban'] = $jawaban == "" ? "" : $jawaban;
			if (trim($jawabanField) == trim($value->jawaban)) {
				$master_soal[$value->id_soal]['status'] = "Benar";
			}else{
				$master_soal[$value->id_soal]['status'] = "Salah";
			}

			switch ($value->jawaban) {
				case 'A':
				$master_soal[$value->id_soal]['jawabanSoal']= $value->jawaban. ". " . $value->opsi_a;
				break;

				case 'B':
				$master_soal[$value->id_soal]['jawabanSoal']= $value->jawaban. ". " . $value->opsi_b;
				break;

				case 'C':
				$master_soal[$value->id_soal]['jawabanSoal']= $value->jawaban. ". " . $value->opsi_c;
				break;

				case 'D':
				$master_soal[$value->id_soal]['jawabanSoal']= $value->jawaban. ". " . $value->opsi_d;
				break;

				case 'E':
				$master_soal[$value->id_soal]['jawabanSoal']= $value->jawaban. ". " . $value->opsi_e;
				break;

				default:
							# code...
				break;
			}

		}

		$hasil->master_soal = $master_soal;

		$data = [
			'ujian' => $ujian,
			'hasil' => $hasil,
			'mhs'	=> $mhs
		];
		
		$this->load->view('ujian/cetak_guru', $data);
	}

	public function cetak_detail($id)
	{
		$this->load->library('Pdf');

		$ujian = $this->ujian->getUjianById($id);
		$nilai = $this->ujian->bandingNilai($id);
		$hasil = $this->ujian->HslUjianById($id)->result();

		$data = [
			'ujian'	=> $ujian,
			'nilai'	=> $nilai,
			'hasil'	=> $hasil
		];

		$this->load->view('ujian/cetak_detail', $data);
	}
	
}