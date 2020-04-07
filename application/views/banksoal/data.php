<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?=$subjudul?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">

        </div>
    </div>
    <?=form_open('soal/delete', array('id'=>'bulk'))?>
    <div class="table-responsive px-4 pb-3" style="border: 0">
        <table id="soal" class="w-100 table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th width="25">No.</th>
                <th>Guru</th>
                <th>Mata Pelajaran</th>
                <th class="text-center">Aksi</th>
            </tr>        
        </thead>
        <tfoot>
            <tr>
                <th width="25">No.</th>
                <th>Guru</th>
                <th>Mata Pelajaran</th>
                <th class="text-center">Aksi</th>
            </tr>
        </tfoot>
        </table>
    </div>
    <?=form_close();?>
</div>

<script src="<?=base_url()?>assets/dist/js/app/banksoal/list.js"></script>
