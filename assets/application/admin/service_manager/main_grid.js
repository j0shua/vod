jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่วิชา',
                name: 'subj_id',
                width: 52,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อวิชา',
                name: 'title',
                width: 220,
                sortable: true,
                align: 'left'
            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 250,
                align: 'left'

            }
        ],
        sortname: "subj_id",
        sortorder: "asc",
        usepager: true,
        title: '',
        useRp: true,
        rp: 20,
        showTableToggleBtn: true,
        width: '100%',
        height: 600,
        singleSelect: true,
        rpOptions: [10, 15, 20, 30, 50]
    });

});



