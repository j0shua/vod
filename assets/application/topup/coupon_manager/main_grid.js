jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่คูปอง',
                name: 'cid',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'รหัสคูปอง',
                name: 'coupon_code',
                width: 100,
                sortable: true,
                align: 'left'


            }, {
                display: 'เวลาที่สร้าง',
                name: 'create_time',
                width: 110,
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
        sortname: "cid",
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



