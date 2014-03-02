var preview = null;
var obj_for_preview = "#explanation,.array_resource,#title,#subj_id,#la_id,#chapter_id,#degree_id";
var not_edit = true;
// onload  =================================================================
$(function() {
    $(".btn-help").tooltip({
        track: true
    });
    // rerun_question_number();
    /**
     *ตั้งค่าเริ่มต้น
     */
    $("#sortable").sortable({
        create: function() {
            rerun_li_number();
        },
        stop: function() {
            rerun_li_number();
        },
        change: function() {
            preview_pass = false;
        }
    });
    var preview_pass = false;
    $(".preview_effect").change(function() {
        preview_pass = false;
    });
    $("#explanation").markItUp(markItUpSettings);

    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 960,
        height: 500
    });

    /**
     * ตรวจเช็คก่อนบันทึก
     */

    $("#main-form").submit(function() {
        var error_msg = ["== เตือน =="];
        var bvalid = true;
        $("#title").val($.trim($("#title").val()));
        if ($("#title").val() === '') {
            bvalid = false;
            error_msg.push("จำเป้นต้องมีชื่อ");
        }
        if ($("#explanation").val() === '') {
            bvalid = false;
            error_msg.push("จำเป็นต้องใส่ข้อมูลคำชี้แจงให้ครบ");
        }
        if (bvalid) {
            if (!preview_pass) {
                alert('โปรดกด "ดูตัวอย่าง" ก่อน บันทึกในงานนี้');
                return false;
            }
            return true;
        } else {
            alert(error_msg.join("\n"));
            return false;
        }
    });
    $("#btn_preview").click(function() {
        preview();
    });
    /**
     * โปรแกรม preview
     */
    preview = function() {
        data = $(obj_for_preview).serialize();

        data += '&render_type_id=1';

        $('body').showLoading();
        $.ajax({
            type: "POST",
            url: ajax_preview_url,
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
        });
        $('body').hideLoading();
    };

    /**
     * ปุ่มลบ โจทย์,เนื้อหา,ตอน
     */
    $("body").delegate(".btn-delete-me", "click", function() {
        $(this).parent().remove();
        rerun_li_number();
    });
    /**
     * ปุ่มเพิ่ม โจทย์,เนื้อหา
     */
    $("#btn_search_resource").click(function() {
        resource_browser();
    });
    /**
     * ปุ่มเพิ่ม ตอน
     */
    $("#btn_add_section").click(function() {
        $('body').showLoading();
        var data = '';
        $.ajax({
            type: "POST",
            url: ajax_li_section_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            if (json.status) {
                $("#sortable").append(json.li);
                $("#sortable").sortable();
            }

        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
        }).always(function() {

            rerun_li_number();
        });
        $('body').hideLoading();
    });
    $("#btn_add_resource_bk").click(function() {
        $("#dialog-add-resource").dialog({
            modal: true,
            width: 400,
            height: 400,
            buttons: {
                "ตกลง": function() {
                    var data = $(".array_resource").serialize();
                    data += "&resource_id=" + $('#dialog_resource_id').val();
                    var ajax_url = ajax_li_resource_url;
                    if ($('#dialog_resource_type_id').val() === 0) {
                        ajax_url = ajax_li_section_url;
                    }
                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: data,
                        dataType: "json",
                        async: false
                    }).done(function(json) {
                        if (json.status) {
                            $("#sortable").append(json.li);
                            $("#sortable").sortable();
                            $("#dialog-add-resource").dialog("close");
                        }

                    }).fail(function(jqXHR, textStatus) {
                        alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
                        console.log(jqXHR);
                    }).always(function() {
                        $('body').hideLoading();
                        rerun_li_number();
                    });


                },
                "ยกเลิก": function() {
                    $(this).dialog("close");
                }
            }
        });

    });

    $("body").delegate(".array_score_pq", "change", function() {
        summary_score();
    });

    //for standard value =====================================================================
    var change_first = true;
    $("#chapter_id").change(function() {
        if (!change_first) {
            $("#sub_chapter_title").val("");
        } else {
            change_first = false;
        }


        $("#sub_chapter_title").autocomplete({
            source: ajax_sub_chapter_autocomplete_url + "?chapter_id=" + $("#chapter_id").val(),
            minLength: 2,
            select: function(event, ui) {
            }
        });
    });
    $("#subj_id").change(function() {
        var data = "subj_id=" + $(this).val();
        $('body').showLoading();
        $.ajax({
            type: "POST",
            url: ajax_chapter_options_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            add_options("#chapter_id", json.array_options, chapter_id);
            $("#chapter_id").change();

        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        }).always(function() {
            $('body').hideLoading();
        });

    });
    $("#la_id").change(function() {
        var data = "la_id=" + $(this).val();
        $('body').showLoading();
        $.ajax({
            type: "POST",
            url: ajax_subject_options_url,
            data: data,
            dataType: "json",
            async: false
        }).done(function(json) {
            add_options("#subj_id", json.array_options, subj_id);
            $("#subj_id").change();
        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
        }).always(function() {
            $('body').hideLoading();
        });

    }).change();



    function add_options(o, array_options, selectedOption) {

        if (selectedOption === undefined) {
            selectedOption = $(o).val();
        }
        var select = $(o);
        if (select.prop) {
            var options = select.prop('options');
        }
        else {
            var options = select.attr('options');
        }
        $('option', select).remove();

        $.each(array_options, function(val, text) {
            options[options.length] = new Option(text, val);
        });
        select.val(selectedOption);
    }





});
//end on load  =================================================================

//extra function  =================================================================
var rerun_li_number = function() {
    var q_number = 1;
    $(".q-number").each(function(key, value) {
        if ($(this).attr('num_questions') > 1) {
            var num_questions = $(this).attr('num_questions');
            var end_number = q_number + parseInt(num_questions) - 1;
            $(this).html("โจทย์ข้อ " + q_number + "-" + end_number);
            q_number += parseInt(num_questions);
        } else {
            $(this).html("โจทย์ข้อ " + q_number);
            q_number++;
        }

    });
    $(".s-number").each(function(key, value) {
        var number = key + 1;
        $(this).html("ตอนที่ " + number);
    });
    summary_score();
};
var pass_score_input = function() {
    if (not_edit) {
        not_edit &= false;
        return;
    }

    var data = $(".array_resource").serialize();
    $.ajax({
        type: "POST",
        url: ajax_pass_score_input_url,
        data: data,
        dataType: "json",
        async: false,
        beforeSend: function() {
            $('body').showLoading();
        }
    }).done(function(json) {
        console.log(json);
        if (json.status) {
            $("#pass_score").html(json.render);
        } else {
            alert(json.msg);
        }

    }).fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
        console.log(jqXHR);
    }).always(function() {
        $('body').hideLoading();
    });
};
function image_browser(o) {
    $("#inner-dialog").html("<iframe title=\"โปรดรอ\" id=\"iframe-image-browser\" src=\"" + image_manager_iframe_url + "/" + o.id + "\">โปรดรอ</iframe>");
    $("#dialog").dialog({
        title: "Image Browser",
        width: 1150,
        height: 700
    });
    $("#dialog").dialog("open");
}
function insert_image(id, image_file_path) {
    
    $("#" + id).markItUp('insert',
            {
                openWith: insert_image_openWith(image_file_path)
            });
    $("#dialog").dialog("close");
}
// resource search
function resource_browser() {
    $("#inner-dialog").html("<iframe title=\"โปรดรอ\" id=\"iframe-resource-browser\" src=\"" + resource_iframe_url + "\">โปรดรอ</iframe>");
    $("#dialog").dialog({
        title: "เลือกโจทย์/เนื้อหา",
        width: 1150,
        height: 700
    });
    $("#dialog").dialog("open");
}
function add_resource(array_id) {
    var data = 'resource_id=' + array_id.join(",");
    $.ajax({
        type: "POST",
        url: ajax_li_resource_url,
        data: data,
        dataType: "json",
        async: false
    }).done(function(json) {
        if (json.status) {
            $("#sortable").append(json.li);
            $("#sortable").sortable();
        }
        preview_pass = false;


    }).fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
    }).always(function() {
        $('body').hideLoading();
        rerun_li_number();
    });

    $("#dialog").dialog("close");

}
function summary_score() {
    var score = 0;
    $(".array_score_pq").each(function() {

        score += $(this).attr('num_questions') * $(this).val();
    });
    $("#total_score_pq").val(score);
    pass_score_input();

}
    