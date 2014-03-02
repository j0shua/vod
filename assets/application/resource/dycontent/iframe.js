
var fg = null;
$(function() {

    fg = $("#main-table").flexigrid({
        url: ajax_resource_list_url,
        dataType: 'json',
        colModel: [{
                display: '<input id="cb_check_all" type="checkbox" name="cb_check_all">',
                name: 'checkbox',
                width: 25,
                align: 'left'
            }, {
                display: 'เลขที่สื่อ',
                name: 'resource_id',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อสื่อ',
                name: 'title_play',
                width: 300,
                sortable: true,
                align: 'left'
            }, {
                display: 'ชนิด',
                name: 'content_type',
                width: 50,
                sortable: true,
                align: 'left'
            }, {
                display: '#วิดีโอ',
                name: 'count_video_join',
                width: 30,
                align: 'right'
            }, {
                display: '#โจทย์',
                name: 'num_questions',
                width: 35,
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
                width: 90,
                align: 'left'
            }, {
                display: 'เจ้าของ',
                name: 'owner_full_name',
                width: 90,
                align: 'left'

            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 255,
                align: 'left',
                hide: true
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
        height: 465,
        singleSelect: true
    });

    $("#btn_search").click(function() {
        var query = $("#qtype").val() + '=' + $("#query").val();
        query += '&content_type_id=' + $("#content_type_id").val();
        query += '&resource_level=' + $("#resource_level").val();
        query += '&owner_full_name=' + $("#owner_full_name").val();


        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
    $("body").delegate("#cb_check_all", "click", function() {
        var status = true;
        if ($(this).attr("checked") === undefined) {
            status = false;
        }
        $('input[name="cb_resource_id[]"]').each(function() {
            $(this).attr("checked", status);
        })
    });
    $("#btn_add_resource").click(function() {
        var array_id = [];
        $('input[name="cb_resource_id[]"]:checked').each(function() {
            array_id.push($(this).val());
        });
        parent.add_resource(array_id);

    });
    $("#owner_full_name").autocomplete({
        source: ajax_teacher_full_name_url,
        minLength: 2,
        select: function(event, ui) {
            // console.log(ui.item.value);
            $("#owner_full_name").val(ui.item.value);
            $("#btn_search").click();
        }
    });
});