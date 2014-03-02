jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : site_url('resource/video_ftp/ajax_uploads_list'),
        dataType : 'json',
        colModel : [  {
            display : 'filename',
            name : 'filename',
            width : 220,
            sortable : true,
            align : 'left'
        }, {
            display : 'filesize',
            name : 'filesize',
            width : 75,
            sortable : true,
            align : 'center'
        } , {
            display : 'action',
            name : 'action',
            width : 240,
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

    $("#btn-filter").click(function(){
        query = "rid="+$("#role-selection").val();
        fg.flexOptions({
            query: query, 
            qtype: 'custom'
        }).flexReload();

    });  
});



