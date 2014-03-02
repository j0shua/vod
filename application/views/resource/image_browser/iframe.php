<a href="<?php echo $upload_doc_url; ?>" target="_blank" class="btn-submit" id="btn-upload-img">อัพโหลด ภาพ</a>
<div class="clearfix"></div>
<table id="main-table" style="display: none"></table>


<script>
    var fg = null;

    var input_id = "<?php echo $input_form_id; ?>";
    function insert_image(image_file_path){
        parent.insert_image(input_id,image_file_path);
    }
    
    jQuery(function(){
       
        fg = $("#main-table").flexigrid({
            url : site_url('resource/image_browser/ajax_image_list'),
            dataType : 'json',
            colModel : [  {
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

            sortname : "resource_id",
            sortorder : "desc",
            usepager : true,
            title : '',
            useRp : true,
            rp : 20,
            showTableToggleBtn : true,
            width : 780,
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
    });
</script>