jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : site_url('resource/sitevideo_manager/ajax_uploads_list'),
        dataType : 'json',
        colModel : [  {
            display : 'video id',
            name : 'resource_id',
            width : 55,
            sortable : true,
            align : 'right'
        }, {
            display : 'title',
            name : 'title',
            width : 250,
            sortable : true,
            align : 'left'
     
      
        }, {
            display : 'publish',
            name : 'publish',
            width : 50,
            align : 'left'
            
        }, {
            display : 'privacy',
            name : 'privacy',
            width : 50,
            align : 'left'
            
        } , {
            display : 'action',
            name : 'action',
            width : 210,
            align : 'left'
            
        }
        ],

        searchitems : [ {
            display : 'title',
            name : 'title',
            isdefault : true
        }
        
        ],
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



