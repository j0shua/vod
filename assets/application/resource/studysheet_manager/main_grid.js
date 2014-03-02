jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : ajax_grid_url,
        dataType : 'json',
        colModel : [  {
            display : 'เลขที่สื่อ',
            name : 'ts_id',
            width : 55,
            sortable : true,
            align : 'right'
        }, {
            display : 'title',
            name : 'title',
            width : 225,
            sortable : true,
            align : 'left'
     
        }, {
            display : 'file size',
            name : 'h_file_size',
            width : 50,
            sortable : true,
            align : 'left'
        }, {
            display : 'file ext',
            name : 'file_ext',
            width : 35,
            sortable : true,
            align : 'left'
        }, {
            display : 'publish',
            name : 'publish',
            width : 40,
            align : 'left'
            
        }, {
            display : 'privacy',
            name : 'privacy',
            width : 40,
            align : 'left'
            
        } , {
            display : 'action',
            name : 'action',
            width : 255,
            align : 'left'
            
        }
        ],

        sortname : "ts_id",
        sortorder : "desc",
        usepager : true,
        title : '',
        useRp : true,
        rp : 20,
        showTableToggleBtn : true,
        width : 780,
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



