$(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่นักเรียน',
                name: 'uid_student',
                width: 65,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อนักเรียน',
                name: 'student_fullname',
                width: 125,
                sortable: true,
                align: 'right'
            }, {
                display: 'สถานะ',
                name: 'active_text',
                width: 125,
                sortable: true,
                align: 'right'

            }, {
                display: 'action',
                name: 'action',
                width: 255,
                align: 'left'
            }
        ],
        sortname: "c_id",
        sortorder: "desc",
        usepager: true,
        title: '',
        useRp: true,
        rp: 20,
        showTableToggleBtn: true,
        width: '100%',
        height: 400,
        singleSelect: true
    });

    $("#btn_search").click(function() {
        var query = $("#qtype").val() + '=' + $("#query").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
});



