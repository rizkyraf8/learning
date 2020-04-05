var table;

$(document).ready(function () {

    ajaxcsrf();

    table = $("#detail_hasil").DataTable({
        initComplete: function () {
            var api = this.api();
            $('#detail_hasil_filter input')
                .off('.DT')
                .on('keyup.DT', function (e) {
                    api.search(this.value).draw();
                });
        },
        oLanguage: {
            sProcessing: "loading..."
        },
        processing: true,
        serverSide: true,
        ajax: {
            "url": base_url + "hasilujian/NilaiMhs/"+id,
            "type": "POST",
        },
        columns: [
            {
                "data": "id",
                "orderable": false,
                "searchable": false
            },
            { "data": 'nama' },
            { "data": 'nama_kelas' },
            { "data": 'nama_jenjangkelas' },
            { "data": 'jml_benar' },
            { "data": 'nilai' },
        ],
        columnDefs: [
            {
                "targets": 6,
                "data": {
                    "id": "id",
                    "waktuSelesai": "waktuSelesai"
                },
                "render": function (data, type, row, meta) {
                    var btn;
                    if (data.waktuSelesai < 60 || data.statusUjian == "N") {
                        btn = `
                                <a class="btn btn-xs btn-success" href="${base_url}hasilujian/cetak_list/${data.id}" target="_blank">
                                    <i class="fa fa-print"></i> Cetak Hasil
                                </a>`;
                    } else {
                        btn = `<a class="btn btn-xs btn-primary">
                                </i> Masih Dalam Ujian
                            </a>`;
                    }
                    return `<div class="text-center">
                                    ${btn}
                                </div>`;
                }
            },
        ],
        order: [
            [1, 'asc']
        ],
        rowId: function (a) {
            return a;
        },
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            $('td:eq(0)', row).html(index);
        }
    });
});