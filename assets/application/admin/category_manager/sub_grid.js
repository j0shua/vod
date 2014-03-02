jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : site_url('admin/category_manager/ajax_sub_category_list/'+parent_id),
        dataType : 'json',
        colModel : [  {
            display : 'เลขที่',
            name : 'id',
            width : 55,
            sortable : true,
            align : 'right',
            hide:true
        }, {
            display : 'title',
            name : 'title',
            width : 150,
            sortable : true,
            align : 'left'
     
      
      
        } , {
            display : 'action',
            name : 'action',
            width : 150,
            align : 'left'
            
        }
        ],

        searchitems : [ {
            display : 'title',
            name : 'title',
            isdefault : true
        }
        
        ],
        sortname : "id",
        sortorder : "asc",
        usepager : true,
        title : '',
        useRp : true,
        rp : 20,
        showTableToggleBtn : true,
        width : 780,
        height : 400,
        singleSelect: true
    });

    $("#btn-filter").click(function(){
        query = "rid="+$("#role-selection").val();
        fg.flexOptions({
            query: query, 
            qtype: 'custom'
        }).flexReload();

    });  
});



