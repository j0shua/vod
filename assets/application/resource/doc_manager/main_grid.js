jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: '<input id="cb_check_all" type="checkbox" name="cb_check_all">',
                name: 'checkbox',
                width: 31,
                align: 'left'

            }, {
                display: 'เลขที่เอกสาร',
                name: 'resource_id',
                width: 54,
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
                width: 46,
                align: 'left'
            }, {
                display: 'ป้ายกำกับ',
                name: 'tags',
                width: 45,
                align: 'left'
            }, {
                display: 'วิชา',
                name: 'subject_title',
                width: 55,
                align: 'left'

            }, {
                display: 'บทเรียน',
                name: 'chapter_title',
                width: 56,
                align: 'left'

            }, {
                display: 'การนำไปใช้',
                name: 'publish',
                width: 50,
                align: 'left'

            }, {
                display: 'สิทธิการใช้',
                name: 'privacy',
                width: 66,
                align: 'left'
            }, {
                display: '#วิดีโอ',
                name: 'count_video_join',
                width: 30,
                align: 'right'
            }, {
                display: 'เวลาสร้าง',
                name: 'create_time',
                width: 110,
                align: 'left',
                hide: true
            }, {
                display: 'แก้ไขล่าสุด',
                name: 'update_time',
                width: 110,
                align: 'left',
                hide: true

            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 275,
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


    $("#btn_act_grid").click(function() {
        var data = $('input[name="cb_resource_id[]"]').serialize();
        if (data === '') {
            console.log($("#dd_act_grid").val());
            return true;
        }
        if ($("#dd_act_grid").val() === 'delete') {
            if (!confirm("ต้องการลบที่เลือกใช่หรือไม่")) {
                return false;
            }
        }

        var data = $('input[name="cb_resource_id[]"]').serialize();

        data += "&command=" + $("#dd_act_grid").val();

        $.ajax({
            type: "POST",
            url: ajax_act_resource_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            alert(json.msg);
        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        });

        fg.flexReload();
        $('input[type="checkbox"]').attr("checked", false);


    });

    $("body").delegate("#cb_check_all", "click", function() {
        var status = true;
        if ($(this).attr("checked") == undefined) {
            status = false;
        }
        $('input[name="cb_resource_id[]"]').each(function() {
            $(this).attr("checked", status);
        })
    });
});



