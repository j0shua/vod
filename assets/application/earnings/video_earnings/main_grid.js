jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        query: date_query,
        qtype: 'custom',
        colModel: [{
                display: 'เลขที่การเรียน',
                name: 'id',
                width: 60,
                align: 'right'
            }, {
                display: 'เลขที่สื่อ',
                name: 'resource_id',
                width: 55,
                hide: true,
                align: 'right'
            }, {
                display: 'ชื่อวิดีโอ',
                name: 'title',
                width: 225,
                align: 'left'
            }, {
                display: 'เลขที่ผู้ชม',
                name: 'uid_view',
                width: 43,
                align: 'left',
                hide: true
            }, {
                display: 'ชื่อผู้ชม',
                name: 'user_view_fullname',
                width: 173,
                align: 'left'
                
            }, {
                display: 'เลขที่เจ้าของ',
                name: 'uid_owner',
                width: 60,
                align: 'left',
                hide: true
            }, {
                display: 'ชื่อเจ้าของ',
                name: 'user_owner_fullname',
                width: 43,
                align: 'left',
                hide: true
            }, {
                display: 'บาท/ช.ม.',
                name: 'unit_price',
                width: 40,
                align: 'right'
            }, {
                display: 'เวลาการชม',
                name: 'view_times',
                width: 50,
                align: 'left'
            }, {
                display: 'ยอดจากเงินหลัก',
                name: 'money',
                width: 80,
                sortable: true,
                align: 'right'
            }, {
                display: 'ยอดจากเงินโบนัส',
                name: 'money_bonus',
                width: 80,
                sortable: true,
                align: 'right',
                hide: true
            }, {
                display: 'เวลาเข้าชม',
                name: 'first_time',
                width: 115,
                sortable: true,
                align: 'left'
            }, {
                display: 'เวลาออก',
                name: 'last_time',
                width: 115,
                align: 'left',
                hide: true


            }, {
                display: 'action',
                name: 'action',
                width: 30,
                align: 'left',
                hide: true

            }
        ],
        sortname: "id",
        sortorder: "desc",
        usepager: true,
        title: '',
        useRp: true,
        rp: 20,
        showTableToggleBtn: true,
        width: "100%",
        height: 500,
        singleSelect: true
    });

    $("#btn_search").click(function() {
        var query = "from=" + $("#query-date-from").val();
        query += "&to=" + $("#query-date-to").val();
        query += "&is_end=" + $("#query-is-end").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
    $("#query-date-from").datepicker({
        defaultDate: "+0d",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#query-date-to").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#query-date-to").datepicker({
        defaultDate: "+0d",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function(selectedDate) {
            $("#query-date-from").datepicker("option", "maxDate", selectedDate);
        }
    });
});



