$(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: '<input id="cb_check_all" type="checkbox" name="cb_check_all">',
                name: 'checkbox',
                width: 25,
                align: 'left'
            }, {
                display: 'เลขที่วิดีโอ',
                name: 'resource_id',
                width: 45,
                sortable: true,
                align: 'right'
            }, {
                display: 'รหัสวิดีโอ',
                name: 'resource_code',
                width: 40,
                sortable: true,
                align: 'center'
            }, {
                display: 'ภาพตัวอย่าง',
                name: 'thumbnail',
                width: 75,
                sortable: true,
                align: 'center',
                hide: true
            }, {
                display: 'ชื่อวิดีโอ',
                name: 'title_desc',
                width: 180,
                sortable: true,
                align: 'left'
            }, {
                display: 'ค่าบริการ',
                name: 'unit_price',
                width: 45,
                sortable: true,
                align: 'left'
            }, {
                display: 'ป้ายกำกับ',
                name: 'tags',
                width: 45,
                sortable: true,
                align: 'left'
            }, {
                display: 'วิชา',
                name: 'subject_title',
                width: 60,
                sortable: true,
                align: 'left'

            }, {
                display: 'บทเรียน',
                name: 'chapter_title',
                width: 60,
                sortable: true,
                align: 'left'

            }, {
                display: 'ขนาดไฟล์',
                name: 'file_size',
                width: 50,
                align: 'left',
                hide: true
            }, {
                display: 'เวลา',
                name: 'duration',
                width: 45,
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
                width: 205,
                align: 'left'

            }
        ],
        //        searchitems : [ {
        //            display : 'title',
        //            name : 'title',
        //            isdefault : true
        //        }
        //        ],
        sortname: "resource_id",
        sortorder: "desc",
        usepager: true,
        title: '',
        useRp: true,
        rp: 20,
        showTableToggleBtn: true,
        width: '100%',
        height: 400,
        singleSelect: true,
        rpOptions: [10, 15, 20, 30, 50, 100]
    });
    $("#btn_search").click(function() {
        do_search();
    });
    $("#query").keypress(function(e) {
        if (e.which == 13) {
            do_search();
        }
    });
    function do_search() {
        var query = $("#qtype").val() + '=' + $("#query").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();
    }
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
        parent.add_resource_id(input_id, array_id);

    });


});
