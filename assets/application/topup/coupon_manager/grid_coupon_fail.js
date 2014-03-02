jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่การผิด',
                name: 'cfid',
                width: 55,
                sortable: true,
                align: 'right'

            }, {
                display: 'ชื่อ-นามสกุล',
                name: 'user_fullname',
                width: 130,
                sortable: true,
                align: 'left'
            }, {
                display: 'เวลาใช้คูปอง',
                name: 'use_time_fail',
                width: 110,
                sortable: true,
                align: 'left'
            }, {
                display: 'รหัสที่กรอกผิด',
                name: 'coupon_code_fail',
                width: 85,
                sortable: true,
                align: 'left'
            }, {
                display: 'รหัสที่ใช้แล้ว',
                name: 'coupon_code',
                width: 85,
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
        sortname: "uid",
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

    $("body").delegate(".confirm_action", "click", function() {
        if (confirm("ท่านต้องการลบข้อมูลนี้หรือไม่")) {
            return true;
        }
        return false;
    });
});



