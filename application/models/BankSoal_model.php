<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BankSoal_model extends CI_Model {

	public function getDataSoal($id, $guru)
	{
		$this->datatables->select('a.matpel_id, b.nama_matpel, c.nama_guru, c.id_guru');
		$this->datatables->from('tb_soal a');
		$this->datatables->join('matpel b', 'b.id_matpel=a.matpel_id');
		$this->datatables->join('guru c', 'c.id_guru=a.guru_id');
		$this->datatables->group_by('b.id_matpel');
		return $this->datatables->generate();
	}

	public function getSoalById($id)
	{
		$this->db->select('*');
		$this->db->from('tb_soal');
		$this->db->where('matpel_id', $id);
		return $this->db->get()->result();
	}

	public function getMatpelById($id)
	{
		$this->db->select('*');
		$this->db->from('matpel m');
		$this->datatables->join('guru g', 'g.matpel_id=m.id_matpel');
		$this->db->where('g.id_guru', $id);
		return $this->db->get()->row();
	}

}

/* End of file BankSoal_model.php */
/* Location: ./application/models/BankSoal_model.php */