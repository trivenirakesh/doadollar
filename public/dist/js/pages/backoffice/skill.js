
let iii = 0;
var table = $('#example1').DataTable({
    "dom": '<f<t><"cm-dataTables-footer d-flex align-items-center float-right"lip>>',
    "language": {
        "infoFiltered": ""
    },
    "oLanguage": {
        "sInfo": "_START_-_END_ of _TOTAL_",// text you want show for info section
        "sLengthMenu": "_MENU_"
    },
    "responsive": true,
    "autoWidth": false,
    "processing": true,
    "serverSide": true,
    "paging": true,
    "pageLength": 10,
    "ajax": {
        "url": APP_URL + '/manageskill/get',
        "type": "GET",
        "data": function (data) {
        },
    },
    "drawCallback": function (settings) {
        // Here the response
        var response = settings.json;
        var perPageLength = response.perpagedata;
        if (iii == 0) {
            table.page.len(perPageLength).draw();
            iii++;
        }
    },
    "columnDefs": [{
        "targets": [0], //Action column
        "orderable": false, //set not orderable
    }],


});