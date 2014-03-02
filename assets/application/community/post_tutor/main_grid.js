jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : ajax_grid_url,
        dataType : 'json',
        colModel : [  {
            display : 'video id',
            name : 'p_id',
            width : 55,
            sortable : true,
            align : 'right'
        }, {
            display : 'title',
            name : 'title',
            width : 250,
            sortable : true,
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
        sortname : "p_id",
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



