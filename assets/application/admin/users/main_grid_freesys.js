jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: site_url('admin/users/ajax_users_table'),
        dataType: 'json',
        colModel: [{
                display: 'เลขที่ผู้ใช้',
                name: 'uid',
                width: 55,
                sortable: true,
                align: 'right'
            }, {
                display: 'ชื่อ-นามสกุล',
                name: 'user_fullname',
                width: 170,
                sortable: true,
                align: 'left'
            }, {
                display: 'เวลาลงทะเบียน',
                name: 'register_time',
                width: 110,
                sortable: true,
                align: 'left'

            }, {
                display: 'email',
                name: 'email',
                width: 160,
                align: 'left'

            }, {
                display: 'สิทธิ',
                name: 'role_title',
                width: 50,
                align: 'left'
            }, {
                display: 'ใช้งาน',
                name: 'active_text',
                width: 60,
                align: 'left'
            }, {
                display: 'กระทำการ',
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
        sortname: "uid",
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

    $("#search_text").keydown(function(key) {
        if (key.keyCode == 13) {
            do_query();
        }
    });
    $("#btn-filter").click(function() {
        do_query();
    });
    function do_query() {
        query = "rid=" + $("#role_selection").val();
        query = query + "&active=" + $("#active_selection").val();
        query = query + "&search_text=" + $("#search_text").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    }
});



