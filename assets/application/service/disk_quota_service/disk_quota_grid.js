jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: '<input id="cb_check_all" type="checkbox" name="cb_check_all">',
                name: 'checkbox',
                width: 31,
                align: 'left',
                hide: true

            }, {
                display: 'เลขที่บริการ',
                name: 'dq_id',
                width: 50,
                sortable: true,
                align: 'right'

            }, {
                display: 'ชื่อ',
                name: 'full_name',
                width: 163,
                sortable: true,
                align: 'left'
            }, {
                display: 'พื้นที่',
                name: 'disk_quota',
                width: 50,
                sortable: true,
                align: 'left'
            }, {
                display: 'ค่าบริการ',
                name: 'price',
                width: 50,
                sortable: true,
                align: 'left'
            }, {
                display: 'เริ่ม',
                name: 'start_time_text',
                width: 130,
                sortable: true,
                align: 'left'
            }, {
                display: 'สิ้นสุด',
                name: 'end_time_text',
                width: 130,
                sortable: true,
                align: 'left'
            }, {
                display: 'วัน',
                name: 'days',
                width: 40,
                sortable: true,
                align: 'left'
            }, {
                display: 'ให้บริการ',
                name: 'is_active_text',
                width: 70,
                sortable: true,
                align: 'left'

            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 200,
                align: 'left'




            }
        ],
        sortname: "dq_id",
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



