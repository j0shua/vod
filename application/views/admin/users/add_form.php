<div class="grid_12">
    <h1>เพิ่มสมาชิก</h1>
</div>
<div class="hr-940px grid_12 "></div>
<div class="grid_12">
    <?php
    if (is_array($this->session->flashdata('form_error'))) {
        foreach ($this->session->flashdata('form_error') as $v) {
            echo '<p>' . $v . '</p>';
        }
    }
    ?>
    <form id="normalform" action="<?php echo $form_action; ?>" method="post" autocomplete="off">
        <p>
            <label for="username" class="grid_2">Username <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="form_name" name="username" value="<?php echo $form_data['username']; ?>">

        </p>


        <p>
            <label for="password" class="grid_2">password <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="password" id="form_pass" name="password" value="">

        </p>
        <p>
            <label for="email" class="grid_2">Email <span class="important">*</span><span class="less-important">(required)</span></label>
            <input type="text" id="form_email" name="email" value="<?php echo $form_data['email']; ?>">

        </p>
        <input type="submit" value="บันทึก" name="filter" id="btn-filter" class="btn-submit">
        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>
    </form>
</div>


