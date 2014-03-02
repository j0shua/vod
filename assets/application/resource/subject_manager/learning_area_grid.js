jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : ajax_grid_url,
        dataType : 'json',
        colModel : [    {
            display : 'เลขที่กลุ่มสาระ',
            name : 'la_id',
            width : 92,
            sortable : true,
            align : 'right'
        }, {
            display : 'กลุ่มสาระ',
            name : 'title',
            width : 220,
            sortable : true,
            align : 'left'
     
       
        } , {
            display : 'กระทำการ',
            name : 'action',
            width : 250,
            align : 'left'
            
        }
        ],

        sortname : "la_id",
        sortorder : "asc",
        usepager : true,
        title : '',
        useRp : true,
        rp : 20,
        showTableToggleBtn : true,
        width : '100%',
        height : 600,
        singleSelect: true,
        rpOptions: [10, 15, 20, 30, 50,100,150,200,500]
    });

    $("#btn_search").click(function(){
        var query = $("#qtype").val()+'='+$("#query").val();
        query += '&content_type_id='+$("#content_type_id").val()
        fg.flexOptions({
            query:query,
            qtype:'custom'
        }).flexReload();
      
    });
    $("#btn_act_grid").click(function(){
        
        var data = $('input[name="cb_la_id[]"]').serialize();
        data += "&command="+$("#dd_act_grid").val();
        $.ajax({
            type: "POST",
            url: ajax_act_resource_url,
            data: data,
            dataType:"json",
            async:false
        }).done(function(json){
            alert(json.msg);
        }).fail(function(jqXHR, textStatus){
            alert( "Request failed: " + textStatus +" "+jqXHR.status+" "+jqXHR.statusText);
            console.log(jqXHR);
        });
        
        fg.flexReload();
        $('input[type="checkbox"]').attr("checked",false);
        
        
    });
    
    $("body").delegate("#cb_check_all", "click", function(){
        var status = true;
        if($(this).attr("checked")==undefined){
            status = false;
        }
        $('input[name="cb_la_id[]"]').each( function() {
            $(this).attr("checked",status);
        })
    });
});



