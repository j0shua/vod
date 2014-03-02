
function insert_resource_id(resourse_id) {
    parent.insert_resource_id(input_form_id, resourse_id);
}
$(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่ใบงาน',
                name: 'resource_id',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'เลือกใบงาน', 
                name: 'select_action',
                width: 90,
                sortable: true,
                align: 'left'
            }, {
                display: 'ชื่อใบงาน',
                name: 'title',
                width: 225,
                sortable: true,
                align: 'left'
            }, {
                display: '#ข้อสอบ',
                name: 'total_question',
                width: 40,
                sortable: true,
                align: 'right'
            }, {
                display: '#วิดีโอ',
                name: 'count_sheet_video',
                width: 30,
                sortable: true,
                align: 'right'
            }, {
                display: 'ป้ายกำกับ',
                name: 'tags',
                width: 45,
                align: 'left'
            }, {
                display: 'วิชา',
                name: 'subject_title',
                width: 60,
                align: 'left'

            }, {
                display: 'บทเรียน',
                name: 'chapter_title',
                width: 60,
                align: 'left'
            }, {
                display: 'การนำไปใช้',
                name: 'publish',
                width: 60,
                align: 'left'

            }, {
                display: 'สิทธิการใช้',
                name: 'privacy',
                width: 40,
                align: 'left'


            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 255,
                align: 'left'

            }
        ],
        sortname: "resource_id",
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
        query += '&owner_full_name=' + $("#owner_full_name").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
    $("#owner_full_name").autocomplete({
        source: ajax_teacher_full_name_url,
        minLength: 2,
        select: function(event, ui) {
            $("#owner_full_name").val(ui.item.value);
            $("#btn_search").click();
        }
    });

});


