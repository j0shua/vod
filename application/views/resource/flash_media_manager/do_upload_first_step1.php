<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id" type="hidden" name="data[resource_id]" value="<?php echo $data['resource_id']; ?>">
        <input id="title" type="hidden" name="data[title]" value="<?php echo $data['title']; ?>">
        <input id="desc" type="hidden" name="data[desc]" value="<?php echo $data['desc']; ?>">
        <input id="tags" type="hidden" name="data[tags]" value="<?php echo $data['tags']; ?>">
        <input id="publish" type="hidden" name="data[publish]" value="<?php echo $data['publish']; ?>">
        <input id="privacy" type="hidden" name="data[privacy]" value="<?php echo $data['privacy']; ?>">
        <input id="degree_id" type="hidden" name="data[degree_id]" value="<?php echo $data['degree_id']; ?>">
        <input id="la_id" type="hidden" name="data[la_id]" value="<?php echo $data['la_id']; ?>">
        <input id="subj_id" type="hidden" name="data[subj_id]" value="<?php echo $data['subj_id']; ?>">
        
        <input id="chapter_id" type="hidden" name="data[chapter_id]" value="<?php echo $data['chapter_id']; ?>">
        <input id="sub_chapter_title" type="hidden" name="data[sub_chapter_title]" value="<?php echo $data['sub_chapter_title']; ?>">
        <input id="file_size" type="hidden" name="data[file_size]" value="<?php echo $data['file_size']; ?>">
        <input id="folder_path" type="hidden" name="data[folder_path]" value="<?php echo $data['folder_path']; ?>">
        <p>
            <label for="file_path" >ชื่อไฟล์เมนู</label>
            <?php echo form_dropdown('data[file_path]', $file_path_options, '', 'id="file_path"'); ?>
        </p>


        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>