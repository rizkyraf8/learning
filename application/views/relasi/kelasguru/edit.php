<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>kelasguru" class="btn btn-warning btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Batal
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <?=form_open('kelasguru/save', array('id'=>'kelasguru'), array('method'=>'edit', 'guru_id'=>$id_guru))?>
                <div class="form-group">
                    <label>Guru</label>
                    <input type="text" readonly="readonly" value="<?=$guru->nama_guru?>" class="form-control">
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <select id="kelas" multiple="multiple" name="kelas_id[]" class="form-control select2" style="width: 100%!important">
                        <?php 
                        $sk = [];
                        foreach ($kelas as $key => $val) {
                            $sk[] = $val->id_kelas;
                        }
                        foreach ($all_kelas as $m) : ?>
                            <option <?=in_array($m->id_kelas, $sk) ? "selected" : "" ?> value="<?=$m->id_kelas?>"><?=$m->nama_kelas?> - <?=$m->nama_jenjangkelas?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group pull-right">
                    <button type="reset" class="btn btn-flat btn-default">
                        <i class="fa fa-rotate-left"></i> Reset
                    </button>
                    <button id="submit" type="submit" class="btn btn-flat bg-purple">
                        <i class="fa fa-save"></i> Simpan
                    </button>
                </div>
                <?=form_close()?>
            </div>
        </div>
    </div>
</div>

<script src="<?=base_url()?>assets/dist/js/app/relasi/kelasguru/edit.js"></script>