<div class="grid_12">
    <h1 class="main-title"><?php echo anchor('community/board/type/' . $post_data['board_type_id'], ' บอร์ด') ?> | <?php echo $post_data['title']; ?></h1>
    <p>
        <?php echo $post_data['body']; ?>
    </p>
</div>
<div class="hr-940px grid_12 "></div>

<div class="grid_12">
    <?php foreach ($reply_data as $v) { ?>    
        <h4 class="">#<?php echo $v['reply_num']; ?> | <?php echo $v['title']; ?></h4>
        <div>
            <?php echo $v['body']; ?>
        </div>
        <p>by : <?php echo $v['user_post_fullname']; ?> เมื่อ  <?php echo thdate('D-m-Y H:i:s', $v['create_time']); ?> 
            <?php
            if (isset($v['delete_url'])) {
                echo anchor($v['delete_url'], '[ลบโพสนี้]');
            }
            ?>

        </p>

        <div class="hr-940px"></div>
    <?php } ?>    
</div>
<?php if ($is_login) { ?>
    <div class="hr-940px grid_12 "></div>
    <div class="grid_12">
        <form id="normalform" action="<?php echo $form_action; ?>" method="post" >
            <input id="p_id" type="hidden" name="data[p_id]" value="<?php echo $form_data['p_id']; ?>">
            <input id="p_id_parent" type="hidden" name="data[p_id_parent]" value="<?php echo $post_data['p_id']; ?>">
            <input id="board_type_id" type="hidden" name="data[board_type_id]" value="<?php echo $post_data['board_type_id']; ?>">
            <p>
                <label for="title" class="grid_2">หัวข้อ </label>
                <input readonly type="text" id="title" name="data[title]" value="ตอบกลับ : <?php echo $post_data['title']; ?>">
            </p>
            <p>
                <textarea id="body" name="data[body]" ><?php //echo strip_tags(parse_bbcode($form_data['body']));      ?></textarea>
            </p>

            <input type="submit" value="ตอบกลับ" id="btnSubmit" class="btn-submit" >



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
                    error_msg.push("กรุณาใส่ข้อความตอบกลับ"); 
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
<?php } ?>    







