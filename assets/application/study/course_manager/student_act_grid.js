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
                display: 'ส่งเมื่อ',
                name: 'send_time_text',
                width: 125,
                sortable: true,
                align: 'right'
            }, {
                display: 'ให้คะแนนเมื่อ',
                name: 'give_score_time_text',
                width: 125,
                sortable: true,
                align: 'right'
            }, {
                display: 'คะแนนเต็ม',
                name: 'full_score',
                width: 125,
                sortable: true,
                align: 'right' 
            }, {
                display: 'คะแนนได้',
                name: 'get_score',
                width: 125,
                sortable: true,
                align: 'right'
            }, {
                display: 'พรี / โพส / เต็ม',
                name: 'ptest_score',
                width: 125,
                sortable: true,
                align: 'right'
            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 255,
                align: 'left'

            }
        ],
        sortname: "uid_student",
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
    $("#sel_course_act").change(function() {
        window.location = course_act_url + $(this).val();
    });
});



