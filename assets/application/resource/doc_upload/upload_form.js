var formChecker = null;
var upload_complete = false;
var submit_clicked = false;
var resource_upload = null;

function createUploader() {
    resource_upload = new qq.FileUploader({
        multiple: false,
        debug: true,
        params: {
            upload_dir: upload_dir
        },
        element: document.getElementById('resource_upload'),
        action: cgi_bin_url,
        maxConnections: 1,
        allowedExtensions: extension_whitelist,
        sizeLimit: file_size_limit,
        messages: {
            typeError: "{file} ไฟล์มีนามสกุลที่ไม่ถูกต้อง นามสกุลที่ได้รับอนุญาตคือ {extensions} เท่านั้น  ",
            sizeError: "{file} ไฟล์นี้ใหญ่เกินไป, ขนาดของไฟล์ต้องไม่เกิน {sizeLimit}.",
            minSizeError: "{file} ไฟล์มีขนาดเล็กเกินไป, ไฟล์ต้องมีขนาดไม่ต่ำกว่า {minSizeLimit}.",
            emptyError: "{file} ไฟล์ว่างเปล่า, กรุณาเลือกไฟล์อื่น",
            onLeave: "ไฟล์ที่กำลังมีการอัพโหลดถ้าคุณออกจากขณะนี้การอัปโหลดจะถูกยกเลิก"
        },
        onSubmit: function(id, fileName) {

            $("#resource_upload .qq-upload-button").hide();
            var fileNameArray = fileName.split('.');
            $("#title").val(fileNameArray[0]);
        },
        onComplete: function(id, fileName, responseJSON) {
            if (responseJSON.success) {
                $("#vdo_upload .qq-upload-list").empty();
                console.log(responseJSON);
                $("#resume_file").val(responseJSON.resume_file);
                upload_complete = true;
            }
        },
        onCancel: function() {
            $("#resource_upload .qq-upload-button").show();
            $("#title").val("");
        }
    });
}

function submit_click() {
    submit_clicked = true;
    $("#btnSubmit").attr('disabled', 'disabled');
}

function validateForm() {
    if ($("#title").val() == "") {
        $("#btnSubmit").removeAttr('disabled')
        submit_clicked = false;
    }
    if ($("#title").val() != '' && submit_clicked && upload_complete) {
        clearInterval(formChecker);
        formChecker = null;
        $("#main-form").submit();
    }

}

$(function() {
    $(".btn-help").tooltip({
        track: true
    });
    createUploader();
    formChecker = window.setInterval(validateForm, 1000);
    validateForm();
    $("#btnSubmit").click(function() {
        submit_click();
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
