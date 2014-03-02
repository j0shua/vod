jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: site_url('admin/users/ajax_users_table'),
        dataType: 'json',
        colModel: [{
                display: 'uid',
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
                display: 'เงินปกติ',
                name: 'money',
                width: 50,
                sortable: true,
                align: 'left',
                hide: true
            }, {
                display: 'เงินโบนัส',
                name: 'money_bonus',
                width: 50,
                sortable: true,
                align: 'left',
                hide: true
            }, {
                display: 'เงินคงรวม',
                name: 'money_total',
                width: 50,
                sortable: true,
                align: 'left'
            }, {
                display: 'ผลประโยชน์',
                name: 'affiliate_type',
                width: 85,
                sortable: true,
                align: 'left',
                hide: true

            }, {
                display: 'email',
                name: 'email',
                width: 160,
                align: 'left'

            }, {
                display: 'role',
                name: 'role_title',
                width: 50,
                align: 'left'
            }, {
                display: 'ใช้งาน',
                name: 'active_text',
                width: 50,
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
        query = "rid=" + $("#role-selection").val();
        query = query + "&search_text=" + $("#search_text").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    }
});



