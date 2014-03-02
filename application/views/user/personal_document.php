<h1><?php echo $title; ?></h1>
<div class="grid_9">
    <?php if ($image_url) { ?>
        <p>
            <img src="<?php echo $image_url ?>"/>
        </p>
    <?php } else { ?>
        <h2 class="head1">! ยังไม่ได้ทำการอัพโหลดรูปภาพ</h2>
    <?php } ?>
    <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="main_form" enctype="multipart/form-data">
        <input type="hidden" value="<?php echo $document_name; ?>"  name="document_name">
        <input type="hidden" value="<?php echo $uid; ?>"  name="uid">

        <p>
            <label for="personal_document" style="width: 250px;"><?php echo $document_label; ?></label>

            <input id="personal_document" type="file" name="personal_document" size="20" />
        </p>

        <input type="submit" class="btn-submit" id="form_submit" name="submit" value="บันทึก">
        <a class="btn-a" href="<?php echo $cancel_url; ?>">ยกเลิก</a>
        <p class="hide important" id="response"></p>
    </form>
</div>