$(function() {
    $(".btn-help").tooltip({
        track: true
    });
    $("#main-form").submit(function() {
        var b_valid = true;
        var error_msg = ["== โปรดตรวจสอบข้อมูล =="];
        $("#title").val($.trim($("#title").val()));
        if ($("#title").val() === '') {
            b_valid = false;
            error_msg.push("ต้องใส่ชื่อเอกสาร");
        }
        if (b_valid) {
            return true;
        } else {
            alert(error_msg.join("\n"));
            return false;
        }

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