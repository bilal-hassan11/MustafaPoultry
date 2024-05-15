<style>
    .dataTables_wrapper>.row {
        justify-content: center;
        align-items: center;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 10px;
    }

    .dt_table tr td,
    .dt_table tr th {
        vertical-align: middle;
    }

    .dt_table .table-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
</style>
<script src="{{ get_asset('admin_assets') }}/js/dataTable_bundled.min.js"></script>
{{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script>  --}}
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
{{-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script> --}}

<script>
    var dtable = $("table.dt_table").DataTable({
        scrollX: false,//!0,
        responsive: true,
        lengthMenu: [
            [50, 100, 200, -1],
            [50, 100, 200, "All"]
        ],
        "paging": false,
        "bInfo" : false,
        //"ordering": false,
        //"info": false,
        //"searching" : false,
        buttons: ["copy", "print", "pdf", "csv", 'excel'],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });
    dtable.buttons().container().prependTo(".dataTables_wrapper .col-md-6:eq(0)");
</script>