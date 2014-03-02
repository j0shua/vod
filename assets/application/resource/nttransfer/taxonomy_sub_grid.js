jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่บท',
                name: 'tid',
                width: 63,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อบท',
                name: 'title_play',
                width: 237,
                sortable: true,
                align: 'left'


            }, {
                display: 'แสดง',
                name: 'publish',
                width: 50,
                align: 'left'

            }, {
                display: 'น้ำหนัก',
                name: 'weight',
                width: 50,
                align: 'left',
                sortable: true,
                hide: true

            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 195,
                align: 'left'

            }
        ],
        searchitems: [{
                display: 'title',
                name: 'title',
                isdefault: true
            }

        ],
        sortname: "tid",
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

