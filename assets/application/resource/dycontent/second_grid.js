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
                width: 38,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อสื่อ',
                name: 'title_play',
                width: 165,
                sortable: true,
                align: 'left'

            }, {
                display: 'ชนิด',
                name: 'content_type',
                width: 35,
                align: 'left'
            }, {
                display: 'จำนวนโจทย์',
                name: 'num_questions',
                width: 65,
                align: 'right'


            }, {
                display: 'การนำไปใช้',
                name: 'publish',
                width: 55,
                align: 'left'

            }, {
                display: 'สิทธิการใช้',
                name: 'privacy',
                width: 80,
                align: 'left'



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
        if ($(this).attr("checked") == undefined) {
            status = false;
        }
        $('input[name="cb_resource_id[]"]').each(function() {
            $(this).attr("checked", status);
        })
    });
});



