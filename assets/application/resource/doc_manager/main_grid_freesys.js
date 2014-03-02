jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : site_url('resource/doc_manager/ajax_uploads_list'),
        dataType : 'json',
        colModel : [  {
            display : 'เลขที่สื่อ',
            name : 'resource_id',
            width : 45,
            sortable : true,
            align : 'right'
        }, {
            display : 'ชื่อสื่อ',
            name : 'title',
            width : 215,
            sortable : true,
            align : 'left'
     
        }, {
            display : 'ขนาดไฟล์',
            name : 'h_file_size',
            width : 50,
            sortable : true,
            align : 'left',
            hide:true
        }, {
            display : 'รูปแแบไฟล์',
            name : 'file_ext',
            width : 55,
            sortable : true,
            align : 'left'
        }, {
            display : 'จัดพิมพ์',
            name : 'publish',
            width : 40,
            align : 'left'
            
        }, {
            display : 'ส่วนตัว',
            name : 'privacy',
            width : 40,
            align : 'left'
            
        } , {
            display : 'ปฏิบัติ',
            name : 'action',
            width : 255,
            align : 'left'
            
        }
        ],

        sortname : "resource_id",
        sortorder : "desc",
        usepager : true,
        title : '',
        useRp : true,
        rp : 20,
        showTableToggleBtn : true,
        width : '100%',
        height : 400,
        singleSelect: true
    });

    $("#btn_search").click(function(){
        var query = $("#qtype").val()+'='+$("#query").val();
        fg.flexOptions({
            query:query,
            qtype:'custom'
        }).flexReload();
      
    });
});



