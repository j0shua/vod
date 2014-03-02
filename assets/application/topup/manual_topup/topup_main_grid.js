jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : ajax_grid_url,
        dataType : 'json',
        colModel : [  {
            display : '#การโอน',
            name : 'mt_id',
            width : 40,
            sortable : true,
            align : 'right'
        }, {
            display : 'วันที่โอน',
            name : 'transfer_time',
            width : 102,
            sortable : true,
            align : 'left'
        }, {
            display : 'ผู้ได้รับการเติม',
            name : 'fullname_use',
            width : 130,
            sortable : true,
            align : 'left'
        }, {
            display : 'ผู้โอน',
            name : 'fullname_informant',
            width : 130,
            sortable : true,
            align : 'left'
        }, {
            display : 'วันที่แจ้ง',
            name : 'inform_time',
            width : 102,
            sortable : true,
            align : 'left',
            hide :true
        }, {
            display : 'วันที่เติมเงิน',
            name : 'topup_time',
            width : 102,
            sortable : true,
            align : 'left'
        }, {
            display : 'ประเภทการเติม',
            name : 'topup_type',
            width : 80,
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
        sortname : "mt_id",
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

    $("#btn-filter").click(function(){
        query = "rid="+$("#role-selection").val();
        fg.flexOptions({
            query: query, 
            qtype: 'custom'
        }).flexReload();

    });  
});



