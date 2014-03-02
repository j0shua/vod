jQuery(function(){
    var fg = $("#main-table").flexigrid({
        url : ajax_grid_url,
        dataType : 'json',
        query:date_query,
        qtype:'custom',
        colModel : [   {
            display : 'เลขที่สมาชิก',
            name : 'uid',
            width : 60,
            align : 'left'
            
        }, {
            display : 'ชื่อสมาชิก',
            name : 'user_downline_fullname',
            width : 203,
            align : 'left'
        }, {
            display : 'จำนวนเงินเติม',
            name : 'money_topup',
            width : 100,
            align : 'left'
        }, {
            display : 'รายได้',
            name : 'money_earnings',
            width : 100,
            align : 'left'
        } , {
            display : 'action',
            name : 'action',
            width : 30,
            align : 'left',
            hide:true
        }
        ],
        
        sortname : "uid",
        sortorder : "desc",
        usepager : true,
        title : '',
        useRp : true,
        rp : 20,
        showTableToggleBtn : true,
        width : 780,
        height : 500,
        singleSelect: true
    });
    
    $("#btn_search").click(function(){
        var query = "from="+$("#query-date-from").val();
        query += "&to="+$("#query-date-to").val();
                
        fg.flexOptions({
            query:query,
            qtype:'custom'
        }).flexReload();
            
    });
    $( "#query-date-from" ).datepicker({
        defaultDate: "+0d",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            $( "#query-date-to" ).datepicker( "option", "minDate", selectedDate );
        }
    });
    $( "#query-date-to" ).datepicker({
        defaultDate: "+0d",
        changeMonth: true,
        numberOfMonths: 1,
        onClose: function( selectedDate ) {
            $( "#query-date-from" ).datepicker( "option", "maxDate", selectedDate );
        }
    });
});



