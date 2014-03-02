<h1><?php echo $title; ?></h1>

<div class="grid_12">
    <p><?php echo nl2br($form_data['data']); ?></p>

    <p>กำหนดส่ง : <?php echo $form_data['deadline']; ?></p>
    <?php echo $do_act_link; ?>

</div>