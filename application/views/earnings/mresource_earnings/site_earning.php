<h1 class="page-header"><?php echo $title; ?></h1>
<div class="row">
    <form action="<?php echo $form_action; ?>">

        <input type="text" name="from" value="<?php echo $from_text; ?>">
        <input type="text" name="to"  value="<?php echo $to_text; ?>">
        <button type="submit">กรอง</button>
    </form>
    <p><?php echo $date_range_text; ?></p>
</div>