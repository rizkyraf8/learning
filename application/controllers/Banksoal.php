<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banksoal extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) {
			redirect('auth');
		} else if (!$this->ion_auth->is_admin()) {
			show_error('Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href="' . base_url('dashboard') . '">Kembali ke menu awal</a>', 403, 'Akses Terlarang');
		}
        $this->load->library(['datatables', 'form_validation']); // Load Library Ignited-Datatables
        $this->load->helper('my'); // Load Library Ignited-Datatables
        $this->load->model('BankSoal_model', 'soal');
        $this->form_validation->set_error_delimiters('', '');
    }

    public function output_json($data, $encode = true)
    {
        if ($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
    {
    	$user = $this->ion_auth->user()->row();
    	$data = [
    		'user' => $user,
    		'judul'    => 'Soal',
    		'subjudul' => 'Bank Soal'
    	];

    	$this->load->view('_templates/dashboard/_header.php', $data);
    	$this->load->view('banksoal/data');
    	$this->load->view('_templates/dashboard/_footer.php');
    }

    public function data($id = null, $guru = null)
    {
        $this->output_json($this->soal->getDataSoal($id, $guru), false);
    }

    public function cetak($id = null, $guru = "")
    {
        $this->load->library('Pdf');

        $matpel = $this->soal->getMatpelById($guru);
        $soal = $this->soal->getSoalById($id);

        $data = [
            'matpel' => $matpel,
            'soal' => $soal,
        ];
        
        $this->load->view('banksoal/cetak', $data);
    }

}

/* End of file Banksoal.php */
/* Location: ./application/controllers/controllername.php */