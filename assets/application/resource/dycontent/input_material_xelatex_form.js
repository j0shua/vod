var preview = null;
$(function() {
    $(".btn-help").tooltip({
        track: false,
        hide: {duration: 5000}
    });
    var preview_pass = false;
    $(".content-markItUp").change(function() {
        preview_pass = false;
    });
    var content_header = $("#content_header");
    var error_msg = [];
    $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 960,
        height: 500
    });
    content_header.markItUp(markItUpSettings);
    $("#main-form").submit(function() {

        error_msg = ["== เตือน =="];
        var bvalid = true;
        if ($("#title").val() === '') {
            bvalid = false;
            error_msg.push("จำเป้นต้องมีชื่อ");
        }
        if (content_header.val() === '') {
            bvalid = false;
            error_msg.push("จำเป็นต้องใส่ข้อมูลเนื้อหาให้ครบ");
        }

        if (bvalid) {
            if (!preview_pass) {
                alert('โปรดกด "ดูตัวอย่าง" ก่อน บันทึกเนื้อหา');
                return false;
            } else {
                return true;
            }

        } else {
            alert(error_msg.join("\n"));
            return false;
        }


    });
    $("#btnpreview").click(function() {
        preview();
    });

    preview = function() {
        var data = "data[content_header]=" + encodeURIComponent(content_header.val());
        data += "&data[content_type_id]=" + $("#content_type_id").val();
        data += "&" + $(".content-question").serialize();
        data = data + "&render_type_id=" + $("#render_type_id").val();

        $('body').showLoading();
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
    };
    $("#btnimage").click(function() {
        content_header.markItUp('insert',
                {
                    openWith: $("#title").val()
                });
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
function image_browser(o) {
    $("#inner-dialog").html("<iframe title=\"โปรดรอ\" id=\"iframe-image-browser\" src=\"" + image_browser_iframe_url + "/" + o.id + "\">โปรดรอ</iframe>");
    $("#dialog").dialog({
        title: "เลือกรูปภาพ",
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
    