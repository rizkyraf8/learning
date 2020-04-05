<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ujian_model extends CI_Model {
    
    public function getDataUjian($id)
    {
        $this->datatables->select('a.id_ujian, a.token, a.nama_ujian, b.nama_matpel, a.jumlah_soal, CONCAT(a.tgl_mulai, " <br/> (", a.waktu, " Menit)") as waktu, a.jenis');
        $this->datatables->from('m_ujian a');
        $this->datatables->join('matpel b', 'a.matpel_id = b.id_matpel');
        if($id!==null){
            $this->datatables->where('guru_id', $id);
        }
        return $this->datatables->generate();
    }
    
    public function getListUjian($id, $kelas)
    {
        $this->datatables->select("a.id_ujian, e.nama_guru, d.nama_kelas, a.nama_ujian, b.nama_matpel, a.jumlah_soal, CONCAT(a.tgl_mulai, ' <br/> (', a.waktu, ' Menit)') as waktu, (SELECT COUNT(id) FROM h_ujian h WHERE h.siswa_id = {$id} AND h.ujian_id = a.id_ujian) AS ada, TIME_TO_SEC(TIMEDIFF((SELECT tgl_selesai FROM h_ujian h WHERE h.siswa_id = {$id} AND h.ujian_id = a.id_ujian), NOW())) as waktuSelesai, (SELECT status FROM h_ujian h WHERE h.siswa_id = {$id} AND h.ujian_id = a.id_ujian) AS statusUjian");
        $this->datatables->from('m_ujian a');
        $this->datatables->join('matpel b', 'a.matpel_id = b.id_matpel');
        $this->datatables->join('kelas_guru c', "a.guru_id = c.guru_id");
        $this->datatables->join('kelas d', 'c.kelas_id = d.id_kelas');
        $this->datatables->join('guru e', 'e.id_guru = c.guru_id');
        $this->datatables->where('d.id_kelas', $kelas);
        $this->datatables->group_by('a.id_ujian');
        return $this->datatables->generate();
    }

    public function getUjianById($id)
    {
        $this->db->select('*');
        $this->db->from('m_ujian a');
        $this->db->join('guru b', 'a.guru_id=b.id_guru');
        $this->db->join('matpel c', 'a.matpel_id=c.id_matpel');
        $this->db->where('id_ujian', $id);
        return $this->db->get()->row();
    }

    public function getIdGuru($nip)
    {
        $this->db->select('id_guru, nama_guru')->from('guru')->where('nip', $nip);
        return $this->db->get()->row();
    }

    public function getJumlahSoal($guru)
    {
        $this->db->select('COUNT(id_soal) as jml_soal');
        $this->db->from('tb_soal');
        $this->db->where('guru_id', $guru);
        return $this->db->get()->row();
    }

    public function getIdSiswa($nim)
    {
        $this->db->select('*');
        $this->db->from('siswa a');
        $this->db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->db->join('jenjangkelas c', 'b.jenjangkelas_id=c.id_jenjangkelas');
        $this->db->where('nim', $nim);
        return $this->db->get()->row();
    }

    public function getIdSiswaById($id)
    {
        $this->db->select('*');
        $this->db->from('siswa a');
        $this->db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->db->join('jenjangkelas c', 'b.jenjangkelas_id=c.id_jenjangkelas');
        $this->db->where('id_siswa', $id);
        return $this->db->get()->row();
    }

    public function HslUjian($id, $mhs)
    {
        $this->db->select('*, UNIX_TIMESTAMP(tgl_selesai) as waktu_habis');
        $this->db->from('h_ujian');
        $this->db->where('ujian_id', $id);
        $this->db->where('siswa_id', $mhs);
        return $this->db->get();
    }

    public function getSoal($id)
    {
        $ujian = $this->getUjianById($id);
        $order = $ujian->jenis==="acak" ? 'rand()' : 'id_soal';

        $this->db->select('id_soal, soal, file, tipe_file, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, jawaban');
        $this->db->from('tb_soal');
        $this->db->where('guru_id', $ujian->guru_id);
        $this->db->where('matpel_id', $ujian->matpel_id);
        $this->db->order_by($order);
        $this->db->limit($ujian->jumlah_soal);
        return $this->db->get()->result();
    }

    public function ambilSoal($pc_urut_soal1, $pc_urut_soal_arr)
    {
        $this->db->select("*, {$pc_urut_soal1} AS jawaban");
        $this->db->from('tb_soal');
        $this->db->where('id_soal', $pc_urut_soal_arr);
        return $this->db->get()->row();
    }

    public function getJawaban($id_tes)
    {
        $this->db->select('list_jawaban');
        $this->db->from('h_ujian');
        $this->db->where('id', $id_tes);
        return $this->db->get()->row()->list_jawaban;
    }

    public function getHasilUjian($nip = null)
    {
        $this->datatables->select('b.id_ujian, b.nama_ujian, b.jumlah_soal, CONCAT(b.waktu, " Menit") as waktu, b.tgl_mulai');
        $this->datatables->select('c.nama_matpel, d.nama_guru');
        $this->datatables->from('h_ujian a');
        $this->datatables->join('m_ujian b', 'a.ujian_id = b.id_ujian');
        $this->datatables->join('matpel c', 'b.matpel_id = c.id_matpel');
        $this->datatables->join('guru d', 'b.guru_id = d.id_guru');
        $this->datatables->group_by('b.id_ujian');
        if($nip !== null){
            $this->datatables->where('d.nip', $nip);
        }
        return $this->datatables->generate();
    }

    public function HslUjianById($id, $dt=false)
    {
        if($dt===false){
            $db = "db";
            $get = "get";
        }else{
            $db = "datatables";
            $get = "generate";
        }
        
        $this->$db->select('d.id, a.nama, b.nama_kelas, c.nama_jenjangkelas, d.jml_benar, d.nilai, TIME_TO_SEC(TIMEDIFF(NOW(), d.tgl_selesai)) as waktuSelesai, d.status statusUjian');
        $this->$db->from('siswa a');
        $this->$db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->$db->join('jenjangkelas c', 'b.jenjangkelas_id=c.id_jenjangkelas');
        $this->$db->join('h_ujian d', 'a.id_siswa=d.siswa_id');
        $this->$db->where(['d.ujian_id' => $id]);
        return $this->$db->$get();
    }

    public function HslUjianByIdUjian($id, $dt=false)
    {
        if($dt===false){
            $db = "db";
            $get = "get";
        }else{
            $db = "datatables";
            $get = "generate";
        }
        
        $this->$db->select('d.id, a.nama, b.nama_kelas, c.nama_jenjangkelas, d.jml_benar, d.nilai, TIME_TO_SEC(TIMEDIFF(NOW(), d.tgl_selesai)) as waktuSelesai, d.status statusUjian, d.siswa_id, d.list_soal, d.list_jawaban, d.ujian_id');
        $this->$db->from('siswa a');
        $this->$db->join('kelas b', 'a.kelas_id=b.id_kelas');
        $this->$db->join('jenjangkelas c', 'b.jenjangkelas_id=c.id_jenjangkelas');
        $this->$db->join('h_ujian d', 'a.id_siswa=d.siswa_id');
        $this->$db->where(['d.id' => $id]);
        return $this->$db->$get();
    }

    public function bandingNilai($id)
    {
        $this->db->select_min('nilai', 'min_nilai');
        $this->db->select_max('nilai', 'max_nilai');
        $this->db->select_avg('FORMAT(FLOOR(nilai),0)', 'avg_nilai');
        $this->db->where('ujian_id', $id);
        return $this->db->get('h_ujian')->row();
    }

    public function listPertanyaan($id)
    {
        $this->db->where_in('id_soal', $id);
        $this->db->order_by('id_soal', "asc");
        return $this->db->get('tb_soal')->result();

    }

}