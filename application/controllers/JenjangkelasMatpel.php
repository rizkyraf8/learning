<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JenjangkelasMatpel extends CI_Controller {

	public function __construct(){
		parent::__construct();
		if (!$this->ion_auth->logged_in()){
			redirect('auth');
		}else if (!$this->ion_auth->is_admin()){
			show_error('Hanya Administrator yang diberi hak untuk mengakses halaman ini, <a href="'.base_url('dashboard').'">Kembali ke menu awal</a>', 403, 'Akses Terlarang');			
		}
		$this->load->library(['datatables', 'form_validation']);// Load Library Ignited-Datatables
		$this->load->model('Master_model', 'master');
		$this->form_validation->set_error_delimiters('','');
	}

	public function output_json($data, $encode = true)
	{
        if($encode) $data = json_encode($data);
        $this->output->set_content_type('application/json')->set_output($data);
    }

    public function index()
	{
		$data = [
			'user' => $this->ion_auth->user()->row(),
			'judul'	=> 'Jenjang Mata Pelajaran',
			'subjudul'=> 'Data Jenjang Mata Pelajaran'
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/jenjangkelasmatpel/data');
		$this->load->view('_templates/dashboard/_footer.php');
    }

    public function data()
    {
        $this->output_json($this->master->getJenjangkelasMatpel(), false);
	}

	public function getJenjangkelasId($id)
	{
		$this->output_json($this->master->getAllJenjangkelas($id));		
	}
	
	public function add()
	{
		$data = [
			'user' 		=> $this->ion_auth->user()->row(),
			'judul'		=> 'Tambah Jenjang Mata Pelajaran',
			'subjudul'	=> 'Tambah Data Jenjang Mata Pelajaran',
			'matpel'	=> $this->master->getMatpel()
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/jenjangkelasmatpel/add');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function edit($id)
	{
		$data = [
			'user' 			=> $this->ion_auth->user()->row(),
			'judul'			=> 'Edit Jenjang Mata Pelajaran',
			'subjudul'		=> 'Edit Data Jenjang Mata Pelajaran',
			'matpel'		=> $this->master->getMatpelById($id, true),
			'id_matpel'		=> $id,
			'all_jenjangkelas'	=> $this->master->getAllJenjangkelas(),
			'jenjangkelas'		=> $this->master->getJenjangkelasByIdMatpel($id)
		];
		$this->load->view('_templates/dashboard/_header.php', $data);
		$this->load->view('relasi/jenjangkelasmatpel/edit');
		$this->load->view('_templates/dashboard/_footer.php');
	}

	public function save()
	{
		$method = $this->input->post('method', true);
		$this->form_validation->set_rules('matpel_id', 'Mata Pelajaran', 'required');
		$this->form_validation->set_rules('jenjangkelas_id[]', 'Jenjangkelas', 'required');
	
		if($this->form_validation->run() == FALSE){
			$data = [
				'status'	=> false,
				'errors'	=> [
					'matpel_id' => form_error('matpel_id'),
					'jenjangkelas_id[]' => form_error('jenjangkelas_id[]'),
				]
			];
			$this->output_json($data);
		}else{
			$matpel_id 	= $this->input->post('matpel_id', true);
			$jenjangkelas_id = $this->input->post('jenjangkelas_id', true);
			$input = [];
			foreach ($jenjangkelas_id as $key => $val) {
				$input[] = [
					'matpel_id' 	=> $matpel_id,
					'jenjangkelas_id'  	=> $val
				];
			}
			if($method==='add'){
				$action = $this->master->create('jenjangkelas_matpel', $input, true);
			}else if($method==='edit'){
				$id = $this->input->post('matpel_id', true);
				$this->master->delete('jenjangkelas_matpel', $id, 'matpel_id');
				$action = $this->master->create('jenjangkelas_matpel', $input, true);
			}
			$data['status'] = $action ? TRUE : FALSE ;
		}
		$this->output_json($data);
	}

	public function delete()
    {
        $chk = $this->input->post('checked', true);
        if(!$chk){
            $this->output_json(['status'=>false]);
        }else{
            if($this->master->delete('jenjangkelas_matpel', $chk, 'matpel_id')){
                $this->output_json(['status'=>true, 'total'=>count($chk)]);
            }
        }
	}
}