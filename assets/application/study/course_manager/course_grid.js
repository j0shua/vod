$(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่หลักสูตร',
                name: 'c_id',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อหลักสูตร',
                name: 'title',
                width: 225,
                sortable: true,
                align: 'left'
            }, {
                display: 'จำนวนนักเรียน',
                name: 'student_count',
                width: 65,
                sortable: true,
                align: 'left'
            }, {
                display: 'จำกัดนักเรียน',
                name: 'enroll_limit_text',
                width: 65,
                sortable: true,
                align: 'left'
            }, {
                display: 'เริ่มเรียน',
                name: 'start_time',
                width: 100,
                sortable: true,
                align: 'left'
            }, {
                display: 'สิ้นสุดการเรียน',
                name: 'end_time',
                width: 100,
                sortable: true,
                align: 'left'
            }, {
                display: 'การนำไปใช้',
                name: 'publish',
                width: 70,
                sortable: true,
                align: 'left'

            }, {
                display: 'กระทำการ',
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



