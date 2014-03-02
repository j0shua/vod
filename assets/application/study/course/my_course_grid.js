jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่อหลักสูตร',
                name: 'c_id',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'title',
                name: 'title',
                width: 225,
                sortable: true,
                align: 'left'
            }, {
                display: 'เริ่มเรียน',
                name: 'start_time',
                width: 100,
                sortable: true,
                align: 'left'
            }, {
                display: 'สิ้นสุดการเรียน',
                name: 'end_time',
                width: 100,
                sortable: true,
                align: 'left'
            }, {
                display: 'ผู้สอน',
                name: 'owner_full_name',
                width: 100,
                sortable: true,
                align: 'left'
            }, {
                display: 'โรงเรียน',
                name: 'owner_school_name',
                width: 100,
                sortable: true,
                align: 'left',
                hide: true
            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 255,
                align: 'left'

            }
        ],
        sortname: "c_id",
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

    $("#btn_search").click(function() {
        var query = $("#qtype").val() + '=' + $("#query").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
});



