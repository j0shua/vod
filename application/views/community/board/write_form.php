<div class="grid_12">
    <h1 class="main-title"><?php echo $form_title; ?></h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
        <input id="p_id" type="hidden" name="data[p_id]" value="<?php echo $form_data['p_id']; ?>">
        <input id="p_id_parent" type="hidden" name="data[p_id_parent]" value="">
        <input id="board_type_id" type="hidden" name="data[board_type_id]" value="<?php echo $form_data['board_type_id']; ?>">
        <p>
            <label for="title" class="grid_2">หัวเรื่อง <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">
        </p>
        <p>

            <textarea id="body" name="data[body]" ><?php echo $form_data['body']; ?></textarea>
        </p>

        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>
<script>
    var error_msg;
    $(function(){
        $("#body").markItUp(markItUpSettings);
        $('#normalform').submit(function(e) {
            //e.preventDefault();
            error_msg = [];
            var b_valid = true;
            $("#title").val($.trim($("#title").val()));
            
            if($("#title").val() == ''){
                b_valid = false;
                error_msg.push("กรุณาใส่หัวเรื่องด้วย"); 
            }
            $("#body").val($.trim($("#body").val()));
            if($("#body").val() == ''){
                b_valid = false;
                error_msg.push("กรุณาใส่เนื้อหา"); 
            }
            if(b_valid){
                return true;
            }else{
                alert(error_msg.join("\n"));
                return false;
            }
            
        });
    
            
        
    });
</script>
<style>

    .markItUp{
        width: 600px;
    }
    #normalform textarea{
        resize: vertical;
        width: 570px;
        height: 200px;
    }
</style>





