<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {

    public function create($table, $data, $batch = false)
    {
        if($batch === false){
            $insert = $this->db->insert($table, $data);
        }else{
            $insert = $this->db->insert_batch($table, $data);
        }
        return $insert;
    }

    public function update($table, $data, $pk, $id = null, $batch = false)
    {
        if($batch === false){
            $insert = $this->db->update($table, $data, array($pk => $id));
        }else{
            $insert = $this->db->update_batch($table, $data, $pk);
        }
        return $insert;
    }

    public function delete($table, $data, $pk)
    {
        $this->db->where_in($pk, $data);
        return $this->db->delete($table);
    }

    /**
     * Data Kelas
     */

    public function getDataKelas()
    {
        $this->datatables->select('id_kelas, nama_kelas, id_jenjangkelas, nama_jenjangkelas');
        $this->datatables->from('kelas');
        $this->datatables->join('jenjangkelas', 'jenjangkelas_id=id_jenjangkelas');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_kelas, nama_kelas, id_jenjangkelas, nama_jenjangkelas');        
        return $this->datatables->generate();
    }

    public function getKelasById($id)
    {
        $this->db->where_in('id_kelas', $id);
        $this->db->order_by('nama_kelas');
        $query = $this->db->get('kelas')->result();
        return $query;
    }

    /**
     * Data Jenjangkelas
     */

    public function getDataJenjangkelas()
    {
        $this->datatables->select('id_jenjangkelas, nama_jenjangkelas');
        $this->datatables->from('jenjangkelas');
        $this->datatables->add_column('bulk_select', '<div class="text-center"><input type="checkbox" class="check" name="checked[]" value="$1"/></div>', 'id_jenjangkelas, nama_jenjangkelas');
        return $this->datatables->generate();
    }

    public function getJenjangkelasById($id)
    {
        $this->db->where_in('id_jenjangkelas', $id);
        $this->db->order_by('nama_jenjangkelas');
        $query = $this->db->get('jenjangkelas')->result();
        return $query;
    }

    /**
     * Data Siswa
     */

    public function getDataSiswa()
    {
        $this->datatables->select('a.id_siswa, a.nama, a.nim, a.email, b.nama_kelas, c.nama_jenjangkelas');
        $this->datatables->select('(SELECT COUNT(id) FROM users WHERE username = a.nim) AS ada');
        $this->datatables->from('siswa a');
        $this->datatables->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->datatables->join('jenjangkelas c', 'b.jenjangkelas_id=c.id_jenjangkelas');
        return $this->datatables->generate();
    }

    public function getSiswaById($id)
    {
        $this->db->select('*');
        $this->db->from('siswa');
        $this->db->join('kelas', 'kelas_id=id_kelas');
        $this->db->join('jenjangkelas', 'jenjangkelas_id=id_jenjangkelas');
        $this->db->where(['id_siswa' => $id]);
        return $this->db->get()->row();
    }

    public function getJenjangkelas()
    {
        $this->db->select('id_jenjangkelas, nama_jenjangkelas');
        $this->db->from('kelas');
        $this->db->join('jenjangkelas', 'jenjangkelas_id=id_jenjangkelas');
        $this->db->order_by('nama_jenjangkelas', 'ASC');
        $this->db->group_by('id_jenjangkelas');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllJenjangkelas($id = null)
    {
        if($id === null){
            $this->db->order_by('nama_jenjangkelas', 'ASC');
            return $this->db->get('jenjangkelas')->result();    
        }else{
            $this->db->select('jenjangkelas_id');
            $this->db->from('jenjangkelas_matpel');
            $this->db->where('matpel_id', $id);
            $jenjangkelas = $this->db->get()->result();
            $id_jenjangkelas = [];
            foreach ($jenjangkelas as $j) {
                $id_jenjangkelas[] = $j->jenjangkelas_id;
            }
            if($id_jenjangkelas === []){
                $id_jenjangkelas = null;
            }
            
            $this->db->select('*');
            $this->db->from('jenjangkelas');
            $this->db->where_not_in('id_jenjangkelas', $id_jenjangkelas);
            $matpel = $this->db->get()->result();
            return $matpel;
        }
    }

    public function getKelasByJenjangkelas($id)
    {
        $query = $this->db->get_where('kelas', array('jenjangkelas_id'=>$id));
        return $query->result();
    }

    /**
     * Data Guru
     */

    public function getDataGuru()
    {
        $this->datatables->select('a.id_guru,a.nip, a.nama_guru, a.email, a.matpel_id, b.nama_matpel, (SELECT COUNT(id) FROM users WHERE username = a.nip OR email = a.email) AS ada');
        $this->datatables->from('guru a');
        $this->datatables->join('matpel b', 'a.matpel_id=b.id_matpel');
        return $this->datatables->generate();
    }

    public function getGuruById($id)
    {
        $query = $this->db->get_where('guru', array('id_guru'=>$id));
        return $query->row();
    }

    /**
     * Data Matpel
     */

    public function getDataMatpel()
    {
        $this->datatables->select('id_matpel, nama_matpel');
        $this->datatables->from('matpel');
        return $this->datatables->generate();
    }

    public function getAllMatpel()
    {
        return $this->db->get('matpel')->result();
    }

    public function getMatpelById($id, $single = false)
    {
        if($single === false){
            $this->db->where_in('id_matpel', $id);
            $this->db->order_by('nama_matpel');
            $query = $this->db->get('matpel')->result();
        }else{
            $query = $this->db->get_where('matpel', array('id_matpel'=>$id))->row();
        }
        return $query;
    }

    /**
     * Data Kelas Guru
     */

    public function getKelasGuru()
    {
        $this->datatables->select('kelas_guru.id, guru.id_guru, guru.nip, guru.nama_guru, GROUP_CONCAT(kelas.nama_kelas) as kelas');
        $this->datatables->from('kelas_guru');
        $this->datatables->join('kelas', 'kelas_id=id_kelas');
        $this->datatables->join('guru', 'guru_id=id_guru');
        $this->datatables->group_by('guru.nama_guru');
        return $this->datatables->generate();
    }

    public function getAllGuru($id = null)
    {
        $this->db->select('guru_id');
        $this->db->from('kelas_guru');
        if($id !== null){
            $this->db->where_not_in('guru_id', [$id]);
        }
        $guru = $this->db->get()->result();
        $id_guru = [];
        foreach ($guru as $d) {
            $id_guru[] = $d->guru_id;
        }
        if($id_guru === []){
            $id_guru = null;
        }

        $this->db->select('id_guru, nip, nama_guru');
        $this->db->from('guru');
        $this->db->where_not_in('id_guru', $id_guru);
        return $this->db->get()->result();
    }

    
    public function getAllKelas()
    {
        $this->db->select('id_kelas, nama_kelas, nama_jenjangkelas');
        $this->db->from('kelas');
        $this->db->join('jenjangkelas', 'jenjangkelas_id=id_jenjangkelas');
        $this->db->order_by('nama_kelas');
        return $this->db->get()->result();
    }
    
    public function getKelasByGuru($id)
    {
        $this->db->select('kelas.id_kelas');
        $this->db->from('kelas_guru');
        $this->db->join('kelas', 'kelas_guru.kelas_id=kelas.id_kelas');
        $this->db->where('guru_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
    /**
     * Data Jenjangkelas Matpel
     */

    public function getJenjangkelasMatpel()
    {
        $this->datatables->select('jenjangkelas_matpel.id, matpel.id_matpel, matpel.nama_matpel, jenjangkelas.id_jenjangkelas, GROUP_CONCAT(jenjangkelas.nama_jenjangkelas) as nama_jenjangkelas');
        $this->datatables->from('jenjangkelas_matpel');
        $this->datatables->join('matpel', 'matpel_id=id_matpel');
        $this->datatables->join('jenjangkelas', 'jenjangkelas_id=id_jenjangkelas');
        $this->datatables->group_by('matpel.nama_matpel');
        return $this->datatables->generate();
    }

    public function getMatpel($id = null)
    {
        $this->db->select('matpel_id');
        $this->db->from('jenjangkelas_matpel');
        if($id !== null){
            $this->db->where_not_in('matpel_id', [$id]);
        }
        $matpel = $this->db->get()->result();
        $id_matpel = [];
        foreach ($matpel as $d) {
            $id_matpel[] = $d->matpel_id;
        }
        if($id_matpel === []){
            $id_matpel = null;
        }

        $this->db->select('id_matpel, nama_matpel');
        $this->db->from('matpel');
        $this->db->where_not_in('id_matpel', $id_matpel);
        return $this->db->get()->result();
    }

    public function getJenjangkelasByIdMatpel($id)
    {
        $this->db->select('jenjangkelas.id_jenjangkelas');
        $this->db->from('jenjangkelas_matpel');
        $this->db->join('jenjangkelas', 'jenjangkelas_matpel.jenjangkelas_id=jenjangkelas.id_jenjangkelas');
        $this->db->where('matpel_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }
}