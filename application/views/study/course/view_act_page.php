    <h1 class="main-title"><?php echo $title; ?></h1>
<div class="hr-940px grid_12 "></div>

<div class="grid_12">

    <p><?php echo nl2br($form_data['data']); ?></p>
    <div class="hr-940px  "></div>
    <p>กำหนดส่ง : <?php echo $form_data['start_time_text'].' ถึง '.$form_data['end_time_text']; ?></p>
    <?php echo $do_act_link; ?>

</div>