jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่หลักสูตร',
                name: 'c_id',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อหลักสูตรการเรียน',
                name: 'title',
                width: 225,
                sortable: true,
                align: 'left'

            }, {
                display: 'รายละเอียด',
                name: 'desc',
                width: 250,
                sortable: true,
                align: 'left'
            }, {
                display: 'จำนวนรับ',
                name: 'enroll_limit_text',
                width: 70,
                sortable: true,
                align: 'left'
            }, {
                display: 'ผู้สอน',
                name: 'full_name_owner',
                width: 100,
                sortable: true,
                align: 'left'
            }, {
                display: 'ชั้น',
                name: 'degree_text',
                width: 100,
                sortable: true,
                align: 'left'
            }, {
                display: 'โรงเรียน',
                name: 'school_name_owner',
                width: 100,
                sortable: true,
                align: 'left',
                hide: true
            }, {
                display: 'การทำการ',
                name: 'action',
                width: 70,
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
        var query = $("#qtype").val() + '=' + $("#query_text").val();
        if ($("#search_degree_id").val() !== '') {
            query = query + "&degree_id=" + $("#search_degree_id").val()
        }
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
});



