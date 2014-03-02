$(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่งาน',
                name: 'ca_id',
                width: 40,
                sortable: true,
                align: 'right',
                hide: true

            }, {
                display: 'วันที่',
                name: 'act_date',
                width: 65,
                sortable: true,
                align: 'left'
            }, {
                display: 'ชื่องาน',
                name: 'title_play',
                width: 200,
                sortable: true,
                align: 'left'

            }, {
                display: 'ประเภทงาน',
                name: 'act_type',
                width: 75,
                align: 'left'
            }, {
                display: 'วิธีสั่งงาน',
                name: 'command_act_type',
                width: 110,
                align: 'left',
                hide: true
            }, {
                display: 'วิธีส่งงาน',
                name: 'send_type',
                width: 90,
                align: 'left'
            }, {
                display: 'คะแนนเต็ม',
                name: 'full_score_text',
                width: 55,
                
                align: 'right'
            }, {
                display: 'ช่วงเวลาทำงาน',
                name: 'time_range',
                width: 180,
                sortable: true,
                align: 'left'
            }, {
                display: 'เริ่มส่งงาน',
                name: 'start_time_text',
                width: 100,
                sortable: true,
                align: 'left',
                hide: true
            }, {
                display: 'สิ้นสุดการส่งงาน',
                name: 'end_time_text',
                width: 100,
                sortable: true,
                align: 'left',
                hide: true
            }, {
                display: 'จำนวนส่ง',
                name: 'count_send',
                width: 40,
                
                align: 'left'

            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 170,
                align: 'left'
            }
        ],
        sortname: "start_time",
        sortorder: "asc",
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



