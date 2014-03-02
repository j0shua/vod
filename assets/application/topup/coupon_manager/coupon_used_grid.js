jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่การใช้คูปอง',
                name: 'clid',
                width: 65,
                sortable: true,
                align: 'right'
            }, {
                display: 'เลขที่คูปอง',
                name: 'cid',
                width: 60,
                sortable: true,
                align: 'left'
            }, {
                display: 'รหัสคูปอง',
                name: 'coupon_code',
                width: 100,
                sortable: true,
                align: 'left'
                 }, {
                display: 'จำนวนเงิน',
                name: 'money',
                width: 80,
                sortable: true,
                align: 'left'
                 }, {
                display: 'จำนวนเงิน bonus',
                name: 'money_bonus',
                width: 80,
                sortable: true,
                align: 'left'

            }, {
                display: 'ใช้งานจาก',
                name: 'use_from',
                width: 60,
                sortable: true,
                align: 'left'
                 }, {
                display: 'ประเภทคูปอง',
                name: 'coupon_type',
                width: 60,
                sortable: true,
                align: 'left'
            }, {
                display: 'เวลาที่ใช้',
                name: 'use_time',
                width: 110,
                sortable: true,
                align: 'left'
            }, {
                display: 'ผู้ใช้',
                name: 'full_name_use',
                width: 170,
                sortable: true,
                align: 'left'

            }, {
                display: 'action',
                name: 'action',
                width: 200,
                align: 'left'

            }
        ],
        searchitems: [{
                display: 'ชื่อ',
                name: 'name',
                isdefault: true
            }

        ],
        sortname: "clid",
        sortorder: "desc",
        usepager: true,
        title: '',
        useRp: true,
        rp: 20,
        showTableToggleBtn: true,
        width: "100%",
        height: 400,
        singleSelect: true
    });

    $("#btn-filter").click(function() {
        query = "rid=" + $("#role-selection").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
});



