var preview = null;
$(function() {
     $(".btn-help").tooltip({
        track: true
    });
    var preview_pass = false;
    $(".content-markItUp").change(function() {
        preview_pass = false;
    });
    function check_content_header() {
        if ($('#tabs-link li').length < 2) {
            $("#content_header_wrapper").hide();
        } else {
            $("#content_header_wrapper").show();
        }

    }
    check_content_header();
    var tabs = $("#tabs").tabs();
    var content_header = $("#content_header");
    var error_msg = [];
    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 960,
        height: 500
    });
    $(".content-markItUp").markItUp(markItUpSettings);


    $("#btnSubmit").click(function() {
        var q_count = 0;
        error_msg = ["== โปรดกรอกข้อมูลดังนี้ =="];
        var bvalid = true;
        if ($("#title").val() == '') {
            bvalid = false;
            error_msg.push("ชื่อข้อมูล");
        }

        $(".tab-subfix-val").each(function(index) {
            q_count++;
            var q_subfix = $(this).val();
            var q_num = index + 1;
            var content_type_id = $("#content-type-id-" + q_subfix).val();
            if ($("#question-" + q_subfix).val() == '') {
                bvalid = false;
                error_msg.push("คำถามข้อที่ " + q_num);
            }
            switch (content_type_id)
            {
                case "2":
                case "3":
                    $('textarea[name^="data[content_questions][' + q_subfix + '][choices]"]').each(function(index) {
                        var c_num = index + 1;
                        if ($(this).val() == '') {
                            bvalid = false;
                            error_msg.push('ตัวเลือกที่ ' + c_num + " ของโจทย์ข้อ " + q_num);
                        }
                    });
                    if ($('input[name="data[content_questions][' + q_subfix + '][true_answers][]"]:checked').length == 0) {
                        bvalid = false;
                        error_msg.push("ยังไม่ได้เลือกคำตอบที่ถูกต้อง ของโจทย์ข้อ " + q_num);
                    }
                    break;
                case "4":
                case "5":
                    $('input[name^="data[content_questions][' + q_subfix + '][true_answers]"]').each(function(index) {
                        var ta_num = index + 1;
                        if ($(this).val() == '') {
                            bvalid = false;
                            error_msg.push('คำตอบที่ ' + ta_num + " ของโจทย์ข้อ " + q_num);
                        }
                    });
                    break;
                case "6":
                    $('input[name^="data[content_questions][' + q_subfix + '][choices]"]').each(function(index) {
                        var c_num = index + 1;
                        if ($(this).val() == '') {
                            bvalid = false;
                            error_msg.push('ตัวเลือกคู่ที่ ' + c_num + " ของโจทย์ข้อ " + q_num);
                        }
                    });
                    $('input[name^="data[content_questions][' + q_subfix + '][true_answers]"]').each(function(index) {
                        var c_num = index + 1;
                        if ($(this).val() == '') {
                            bvalid = false;
                            error_msg.push('คำตอบคู่ที่ ' + c_num + " ของโจทย์ข้อ " + q_num);
                        }
                    });
                    break;
                default:
            }
            if ($("#solve-" + q_subfix).val() == '') {
                bvalid = false;
                error_msg.push("เฉลยโจทย์ข้อที่ " + q_num);
            }


        });

        if (q_count == 0) {
            error_msg.push("โปรดเพิ่มโจทย์ ");
        }
        if (content_header.val() == '' && q_count > 1) {
            bvalid = false;
            error_msg.push("โจทย์นำ");
        }

        if (bvalid) {
            if (!preview_pass) {
                alert('โปรด preview ก่อน บันทึกโจทย์');
            } else {
                $("#content-form").submit();
            }
        } else {
            alert(error_msg.join("\n"));
        }

    });
    $("#btnpreview").click(function() {

        preview();
    });

    preview = function() {
        var data = "data[content_header]=" + encodeURIComponent(content_header.val());
        data += "&" + $(".content-question").serialize();
        //alert($(".content-question").serialize());
        data = data + "&render_type_id=" + $("#render_type_id").val();

        $('body').showLoading({
            hPos: "center",
            vPos: "top"
        });
        $.ajax({
            type: "POST",
            url: ajax_preview_content_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            if (json.status) {
                preview_pass = true;
                $("#dialog").dialog({
                    title: "Preview",
                    width: 960,
                    closeText: "hide"
                });
                $("#dialog").dialog("open");
                $("#inner-dialog").html(json.render);
            } else {
                preview_pass = false;
                $("#dialog").dialog({
                    title: "Preview",
                    width: 810
                });
                $("#dialog").dialog("open");
                $("#inner-dialog").html(json.render);
            }


        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        }).always(function() {
            $('body').hideLoading();
        });
    }
    $("#btnimage").click(function() {
        content_header.markItUp('insert',
                {
                    openWith: $("#title").val()
                });
    });
    $("#btnaddquestion").click(function() {
        $("#dialog-add-question").dialog("open");

    });
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
    function add_question() {
        var data = 'content_type_id=' + $("#dialog-content-type-id").val();
        switch ($("#dialog-content-type-id").val()) {
            case "2":
            case "3":
                data = data + "&choice_num=" + $("#dialog-select-choice-num").val();
                break;
            case "5":
                data = data + "&answer_num=" + $("#dialog-select-answer-num").val();
                break;
            case "6":
                data = data + "&pair_num=" + $("#dialog-select-pair-num").val();
                break;
            default:
        }
        $.ajax({
            type: "POST",
            url: ajax_add_question_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            tabs.tabs('add', '#tab-' + json.tab_subfix, 'โจทย์');
            $('#tab-' + json.tab_subfix).append(json.render);

            rewrite_tab_title();
        });
        $('#render_type_id').change();
        check_content_header();
        tabs.tabs('select', $('#tabs-link li').length - 1);
    }

    $("body").delegate(".btn-delete-me", "click", function() {
        tabs.tabs('remove', tabs.tabs("option", "selected"));
        rewrite_tab_title();
        check_content_header();
    });
    function rewrite_tab_title() {
        $('#tabs-link li').each(function(index) {
            var q_num = index + 1;
            $(this).children().text('โจทย์ข้อ ' + q_num);

        });
    }
    //    $("body").delegate(".btn-preview-me", "click", function() {
    //        var data = $('.preview-me-'+$(this).attr('title')).serialize();
    //        data = data+"&render_type_id="+$("#render_type_id").val();
    //        data = data+"&preview_all=0";
    //        preview(data);
    //    });
    $("#dialog-add-question").dialog({
        autoOpen: false,
        modal: true,
        width: 400,
        height: 300,
        buttons: {
            "เพิ่ม": function() {
                add_question();
                $(this).dialog("close");
            },
            "ยกเลิก": function() {
                $(this).dialog("close");
            }
        }
    });
});
function image_browser(o) {
    $("#inner-dialog").html("<iframe title=\"โปรดรอ\" id=\"iframe-image-browser\" src=\"" + image_browser_iframe_url + "/" + o.id + "\">โปรดรอ</iframe>");
    $("#dialog").dialog({
        title: "เลือกรูปภาพ",
        width: 1100
    });
    $("#dialog").dialog("open");
}
function insert_image(id, image_file_path) {
    var t = "\\includegraphics{" + image_file_path + "}";
    $("#" + id).markItUp('insert',
            {
                openWith: t
            });
    $("#dialog").dialog("close");
}
    