jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: '#การโอน',
                name: 'cid',
                width: 40,
                sortable: true,
                align: 'right'
            }, {
                display: 'วันที่เติมเงิน',
                name: 'create_time',
                width: 102,
                sortable: true,
                align: 'left'
            }, {
                display: 'ผู้เติมเงิน',
                name: 'fullname_use',
                width: 150,
                sortable: true,
                align: 'left'
            }, {
                display: 'GameID',
                name: 'GameID',
                width: 150,
                sortable: true,
                align: 'left'
            }, {
                display: 'TransactionID',
                name: 'TransactionID',
                width: 95,
                align: 'left'
            }, {
                display: 'จำนวนที่เติม',
                name: 'ServiceCost',
                width: 95,
                align: 'left'

            }, {
                display: 'action',
                name: 'action',
                width: 150,
                align: 'left'
            }
        ],
        searchitems: [{
                display: 'ชื่อผู้เติม',
                name: 'fullname_use',
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
        width: '100%',
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



