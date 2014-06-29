var formChecker = null;
var upload_complete = false;
var submit_clicked = false;
var resource_upload = null;

function createUploader() {
    resource_upload = new qq.FileUploader({
        multiple: false,
        debug: true,
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
            $("#title").val(fileName);
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

jQuery(function() {
    createUploader();
    formChecker = window.setInterval(validateForm, 1000);
    validateForm();
    $("#btnSubmit").click(function() {
        submit_click();
    });
    // for inputform 

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
            console.log(json);
            $("#subj_id").children().remove().append(json.options_render);
            $("#subj_id").append(json.options_render);


        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        }).always(function() {
            $('body').hideLoading();
        });

    }).change();

    $("#subj_id").change(function() {
        $("#chapter").autocomplete({
            source: ajax_chapter_autocomplete_url + "?subj_id=" + $("#subj_id").val(),
            minLength: 2,
            select: function(event, ui) {
                //            log( ui.item ?
                //                "Selected: " + ui.item.value + " aka " + ui.item.id :
                //                "Nothing selected, input was " + this.value );
            }
        });
    });
});
