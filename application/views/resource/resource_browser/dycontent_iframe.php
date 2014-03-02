<input type="button" value="แทรกเอกสารที่เลือก" id="btn_add_resource" class="btn-submit" >
<div class="clearfix"></div>
<table id="main-table" style="display: none"></table>


<script>
    var fg = null;

    
    
    $(function(){
       
        fg = $("#main-table").flexigrid({
            url : ajax_resource_list_url,
            dataType : 'json',
            colModel : [  {
                    display : '<input id="cb_check_all" type="checkbox" name="cb_check_all">',
                    name : 'checkbox',
                    width : 25,
                    align : 'left'
                }, {
                    display : 'เลขที่สื่อ',
                    name : 'resource_id',
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
                    display : 'ชนิด',
                    name : 'content_type',
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

            sortname : "resource_id",
            sortorder : "desc",
            usepager : true,
            title : '',
            useRp : true,
            rp : 20,
            showTableToggleBtn : true,
            width : 700,
            height : 320,
            singleSelect: true
        });

        $("#btn_search").click(function(){
            var query = $("#qtype").val()+'='+$("#query").val();
            fg.flexOptions({
                query:query,
                qtype:'custom'
            }).flexReload();
      
        });
        $("body").delegate("#cb_check_all", "click", function(){
            var status = true;
            if($(this).attr("checked")==undefined){
                status = false;
            }
            $('input[name="cb_resource_id[]"]').each( function() {
                $(this).attr("checked",status);
            })
        });
        $("#btn_add_resource").click(function(){
            var array_id=[];
            $('input[name="cb_resource_id[]"]:checked').each( function() {
                array_id.push($(this).val());
            });
            parent.add_resource(array_id);
            
        });
    });
</script>