jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : ajax_grid_url,
        dataType : 'json',
        colModel : [  {
            display : 'เลขที่สื่อ',
            name : 'resource_id',
            width : 40,
            sortable : true,
            align : 'right'
        
        }, {
            display : 'ชื่อวิดีโอ',
            name : 'title',
            width : 200,
            sortable : true,
            align : 'left'
       
        }, {
            display : 'เวลา',
            name : 'duration',
            width : 50,
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
        //        searchitems : [ {
        //            display : 'title',
        //            name : 'title',
        //            isdefault : true
        //        }
        //        ],
        sortname : "resource_id",
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



