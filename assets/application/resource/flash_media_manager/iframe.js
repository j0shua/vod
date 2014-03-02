
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
                display: 'เลขที่เอกสาร',
                name: 'resource_id',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อเอกสาร',
                name: 'title_play',
                width: 190,
                sortable: true,
                align: 'left'

            }, {
                display: 'ขนาดไฟล์',
                name: 'h_file_size',
                width: 45,
                align: 'left',
                hide: true
            }, {
                display: 'รูปแแบบไฟล์',
                name: 'file_ext',
                width: 60,
                align: 'left'
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
                display: 'จำนวนวิดีโอ',
                name: 'count_video_join',
                width: 50,
                align: 'right'
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
        height: 480,
        singleSelect: true
    });

    $("#btn_search").click(function() {
        var query = $("#qtype").val() + '=' + $("#query").val();
        query += '&content_type_id=' + $("#content_type_id").val()
        query += '&resource_level=' + $("#resource_level").val()

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
});