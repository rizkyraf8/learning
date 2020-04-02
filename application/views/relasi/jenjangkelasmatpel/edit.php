<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Form <?=$judul?></h3>
        <div class="box-tools pull-right">
            <a href="<?=base_url()?>jenjangkelasmatpel" class="btn btn-warning btn-flat btn-sm">
                <i class="fa fa-arrow-left"></i> Batal
            </a>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                    <?=form_open('jenjangkelasmatpel/save', array('id'=>'jenjangkelasmatpel'), array('method'=>'edit', 'matpel_id'=>$id_matpel))?>
                <div class="form-group">
                    <label>Mata Pelajaran</label>
                    <input type="text" readonly="readonly" value="<?=$matpel->nama_matpel?>" class="form-control">
                    <small class="help-block text-right"></small>
                </div>
                <div class="form-group">
                    <label>Jenjangkelas</label>
                    <select id="jenjangkelas" multiple="multiple" name="jenjangkelas_id[]" class="form-control select2" style="width: 100%!important">
                        <?php 
                        $sj = [];
                        foreach ($jenjangkelas as $key => $val) {
                            $sj[] = $val->id_jenjangkelas;
                        }
                        foreach ($all_jenjangkelas as $m) : ?>
                            <option <?=in_array($m->id_jenjangkelas, $sj) ? "selected" : "" ?> value="<?=$m->id_jenjangkelas?>"><?=$m->nama_jenjangkelas?></option>
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

<script src="<?=base_url()?>assets/dist/js/app/relasi/jenjangkelasmatpel/edit.js"></script>