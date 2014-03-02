jQuery(function() {
    var fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่งาน',
                name: 'ca_id',
                width: 50,
              
                align: 'right',
                hide: true
            }, {
                display: 'วันที่',
                name: 'act_date',
                width: 65,
              
                align: 'left'
            }, {
                display: 'งาน',
                name: 'title',
                width: 130,
              
                align: 'left'
            }, {
                display: 'ประเภทงาน',
                name: 'act_type',
                width: 50,
               
                align: 'left'

            }, {
                display: 'วิธีการส่ง',
                name: 'send_type',
                width: 70,
              
                align: 'left'

            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 150,
                align: 'left'
            }, {
                display: 'ส่งเมื่อ',
                name: 'send_time_text',
                width: 100,
               
                align: 'left'
            }, {
                display: 'คะแนนที่ได้',
                name: 'get_score',
                width: 50,
               
                align: 'left'
            }, {
                display: 'คะแนนเต็ม',
                name: 'full_score_text',
                width: 45,
              
                align: 'left'
            }, {
                display: 'เวลาเริ่มการส่งงาน',
                name: 'start_time_text',
                width: 100,
              
                align: 'left',
                hide: true
            }, {
                display: 'เวลาสิ้นสุดการส่งงาน',
                name: 'end_time_text',
                width: 100,
               
                align: 'left',
                hide: true
            }, {
                display: 'ช่วงเวลาทำงาน',
                name: 'time_range',
                width: 240,
                
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




