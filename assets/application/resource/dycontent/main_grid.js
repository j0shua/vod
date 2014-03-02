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
                display: 'เลขที่สื่อ',
                name: 'resource_id',
                width: 42,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อสื่อ/ดูตัวอย่าง',
                name: 'title_play',
                width: 163,
                sortable: true,
                align: 'left'

            }, {
                display: 'ชนิด',
                name: 'content_type',
                width: 30,
                align: 'left'
            }, {
                display: 'จำนวนโจทย์',
                name: 'num_questions',
                width: 50,
                align: 'right'
            }, {
                display: '<span title="เพิ่มโจทย์เทียบได้ที่นี่">โจทย์เทียบ</span>',
                name: 'second_dycontent_count',
                width: 45,
                align: 'center'

            }, {
                display: 'แสดงผล',
                name: 'render_type',
                width: 37,
                sortable: true,
                align: 'left',
                hide: true
            }, {
                display: 'ป้ายกำกับ',
                name: 'tags',
                width: 42,
                align: 'left'
            }, {
                display: 'วิชา',
                name: 'subject_title',
                width: 55,
                align: 'left'

            }, {
                display: 'บทเรียน',
                name: 'chapter_title',
                width: 50,
                align: 'left'

            }, {
                display: 'การนำไปใช้',
                name: 'publish',
                width: 50,
                align: 'left'

            }, {
                display: 'สิทธิการใช้',
                name: 'privacy',
                width: 50,
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
                width: 240,
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
        height: 600,
        singleSelect: true,
        rpOptions: [10, 15, 20, 30, 50, 100, 150, 200, 500]
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
        query += '&content_type_id=' + $("#content_type_id").val()
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
        if ($(this).attr("checked") === undefined) {
            status = false;
        }
        $('input[name="cb_resource_id[]"]').each(function() {
            $(this).attr("checked", status);
        });
    });
    $("#dialog-add-question").dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 300,
        buttons: {
            "ตกลง": function() {
                add_question();
                $(this).dialog("close");
            },
            "ยกเลิก": function() {
                $(this).dialog("close");
            }
        }
    });
    function add_question() {
        $("#add_question_form").submit();
    }
    $("#dialog-content-type-id").change(function() {
        switch ($(this).val()) {
            case "2":
            case "3":
                $("#dialog-answer-num").hide();
                $("#dialog-choice-num").show();
                $("#dialog-pair-num").hide();
                $("#dialog-select-choice-num").val(4);
                break;
            case "5":
                $("#dialog-answer-num").show();
                $("#dialog-choice-num").hide();
                $("#dialog-pair-num").hide();
                $("#dialog-select-answer-num").val(2);
                break;
            case "6":
                $("#dialog-answer-num").hide();
                $("#dialog-choice-num").hide();
                $("#dialog-pair-num").show();
                $("#dialog-select-pair-num").val(5);
                break;
            default:
                $("#dialog-choice-num").hide();
                $("#dialog-pair-num").hide();
                $("#dialog-answer-num").hide();
        }
    }).change();
    $("#btnaddquestion").click(function() {
        $("#dialog-add-question").dialog("open");
        return false;
    });
});



