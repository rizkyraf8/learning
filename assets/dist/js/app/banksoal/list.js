var table;

$(document).ready(function() {
  ajaxcsrf();

  table = $("#soal").DataTable({
    initComplete: function() {
      var api = this.api();
      $("#soal_filter input")
        .off(".DT")
        .on("keyup.DT", function(e) {
          api.search(this.value).draw();
        });
    },
    buttons: [
      {
        extend: "copy",
        exportOptions: { columns: [1, 2, 3, 4] }
      },
      {
        extend: "print",
        exportOptions: { columns: [1, 2, 3, 4] }
      },
      {
        extend: "excel",
        exportOptions: { columns: [1, 2, 3, 4] }
      },
      {
        extend: "pdf",
        exportOptions: { columns: [1, 2, 3, 4] }
      }
    ],
    oLanguage: {
      sProcessing: "loading..."
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + "banksoal/data",
      type: "POST"
    },
    columns: [
      {
        data: "matpel_id",
        orderable: false,
        searchable: false
      },
      { targets: 1, data: "nama_guru" },
      { targets: 2, data: "nama_matpel" }
    ],
    columnDefs: [
      {
        targets: 3,
        data: "matpel_id",
        orderable: false,
        render: function(data, type, row, meta) {
          return `<div class="text-center">
                      <a class="btn btn-xs btn-success" href="${base_url}banksoal/cetak/${data}" class="btn btn-xs btn-default">
                          <i class="fa fa-print"></i> Cetak Soal
                      </a>
                  </div>`;
        }
      }
    ],
    order: [[2, "desc"]],
    rowId: function(a) {
      return a;
    },
    rowCallback: function(row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $("td:eq(0)", row).html(index);
    }
  });
});