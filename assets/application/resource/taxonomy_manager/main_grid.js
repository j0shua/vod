jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่ชุดวิดีโอ',
                name: 'tid',
                width: 60,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อชุดวิดีโอ',
                name: 'title_play',
                width: 237,
                sortable: true,
                align: 'left'


            }, {
                display: 'แสดงหน้าเว็บ',
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
                display: '#บท',
                name: 'count_sub',
                width: 50,
                align: 'left'
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
        sortname: "weight",
        sortorder: "asc",
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



