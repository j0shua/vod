<h1><?php echo $form_title; ?></h1>
<div class="grid_12">
    <form class="normal-form" id="main-form" action="<?php echo $form_action; ?>" method="post" >
        <input id="resource_id" type="hidden" name="tid" value="<?php echo $form_data['tid']; ?>">
        <input id="tid_parent" type="hidden" name="data[tid_parent]" value="<?php echo $tid_parent; ?>">
        <p>
            <label for="title">ชื่อบทในชุดวิดีโอ </label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>">
        </p>
        <p>
            <label for="data">เลขที่วิดีโอ </label>
            <input style="" type="text" id="data" name="data[data]" value="<?php echo $form_data['data']; ?>">
            <a id="btn-search-resource" href="#" class="btn-a-small">ค้นหา video</a>
            <a id="btn-psearch-resource" href="#" class="btn-a-small">ค้นหา videp prokru.com</a>

        </p>
        <?php if ($make_money && !$is_parent_site) { ?>
            <p>
                <label for="title">เลขที่บทใน prokru.com </label>
                <input type="text" id="title" name="data[tid_parent_site]" value="<?php echo $form_data['tid_parent_site']; ?>">
            </p>
        <?php } else {
            ?>
            <input type="hidden" id="title" name="data[tid_parent_site]" value="<?php echo $form_data['tid_parent_site']; ?>">
        <?php }
        ?>
        <p>
            <label for="desc">รายละเอียด </label>
            <textarea style="height: 100px;"  id="desc" name="data[desc]" ><?php echo $form_data['desc']; ?></textarea>
        </p>

        <p>
            <label for="publish">การแสดงบนหน้าเว็บ  </label>
            <?php echo form_dropdown('data[publish]', $publish_options, $form_data['publish'], 'id="publish"'); ?>
        </p>
<!--        <p>
            <label for="weight">น้ำหนัก  </label>
        <?php echo form_dropdown('data[weight]', $weight_options, $form_data['weight'], 'id="weight"'); ?>
        </p>-->
        <input type="submit" value="บันทึก" id="btnSubmit" class="btn-submit" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>
<div id="dialog" title="ค้นหาวิดีโอ" style="display: none;" >
    <div id="inner-dialog"></div>
</div>
<style>
    #iframe-resource-browser{
        width: 100%; 
        height: 650px;
        overflow: hidden;
    }

    #main-form input[type=text],#main-form textarea{
        width: 700px;

    }

</style>






