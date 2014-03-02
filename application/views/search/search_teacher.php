<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <h2 style="padding: 5px 0; text-indent: 10px; font-size:24px; height: 28px;background-color: #C3B5FF;" class="head1" >
        <?php echo $summary_text; ?>
    </h2>

    <form action="<?php $form_action ?>" method="get" >
        <?php echo form_dropdown('qtype', $qtype_options, $default_qtype, 'id="qtype"'); ?>
        <input id="search_t" type="text" name="t" value="<?php echo $t; ?>" />
        <input type="submit" value="ค้นหา" class="btn-a-small"/>
    </form>
    <div style="margin-top: 5px;width: 100%;" class="clearfix">
        <ul class="house_teacher">
            <?php
            foreach ($teacher_data['rows'] as $v) {
                $cell = $v['cell'];
                ?>
                <li >
                    <div  class="avatar-img clearfix">  <a href="<?php echo site_url('house/u/' . $cell['uid']); ?>"><img  class="clearfix"  title="<?php echo $cell['first_name'] . ' ' . $cell['last_name'] ?>" src="<?php echo site_url('ztatic/img_avatar_128/' . $cell['uid']); ?>"></a></div>

                    <div class="teacher_detail">

                        <h3>

                            <?php echo anchor('house/u/' . $cell['uid'], $cell['first_name'] . ' ' . $cell['last_name']); ?>

                        </h3>

                        <?php echo ($cell['about_me']) ? '<p>' . $cell['about_me'] . '</p>' : ''; ?>
                        <?php echo ($cell['school_name']) ? '<p>' . $cell['school_name'] . '</p>' : ''; ?>

                    </div>



                </li>


                <?php
            }
            ?>
        </ul>
    </div>
    <div>
        <?php echo $pagination; ?>
    </div>




    <?php
    if (0) {
        echo '<pre>';
        print_r($teacher_data);
        echo '/<pre>';
    }
    ?>
</div>
<script>

    $(function() {
        $("#search_t").autocomplete({
            source: ajax_teacher_full_name_url,
            minLength: 2,
            select: function(event, ui) {

            }
        });
        $("#qtype").change(function() {
            if ($(this).val() === 'full_name') {

                $("#search_t").autocomplete("option", "source", ajax_teacher_full_name_url);
            } else if ($(this).val() === 'school_name') {

                $("#search_t").autocomplete("option", "source", ajax_school_name_url);
            }
        }).change();


    });

</script>

<style>

    .house_teacher li {
        float: left;
        width: 205px;
        border: solid 1px #CCCCCC;
        padding: 8px;
        font-family: thai_sans_literegular;
        font-size: 18px;
        font-weight: bold;
        color: #0272a7;
        border-radius: 2px;
        background-color: #FFFFFF;
        margin-bottom: 10px;
        margin-left: 0px;
        margin-right: 10px;

    }



    .house_teacher .avatar-img{
        height: 128px;
        width: 128px;
        background-color: #ffffff;
        display: block;
        border: solid 1px #CCCCCC;
        padding: 5px;
        border-radius: 2px;
        margin-left: auto;
        margin-right: auto;

    }
    .house_teacher .teacher_detail{
        margin-top: 10px;
        border-top: solid 3px #3399FF;
        padding: 5px;
        height: 100px;
    }
    .house_teacher h3{
        font-size: 24px;
    }
    .house_teacher .teacher_detail p{
        margin-bottom: 2px;
        margin-top: 2px;
        line-height: 22px;
        border-bottom: solid 1px #E8E8E8;
    }




</style>
