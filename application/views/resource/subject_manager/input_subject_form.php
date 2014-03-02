<h1 ><?php echo $title; ?></h1>
<div class="grid_12">
    <form id="main-form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="subj_id" type="hidden" name="data[subj_id]" value="<?php echo $form_data['subj_id']; ?>">
        <input id="la_id" type="hidden" name="data[la_id]" value="<?php echo $form_data['la_id']; ?>">
        <input id="uid_owner" type="hidden" name="data[uid_owner]" value="<?php echo $form_data['uid_owner']; ?>">
        <p>
            <label for="title">ชื่อวิชา</label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">
        </p>
        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >
        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>
</div>