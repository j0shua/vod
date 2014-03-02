<?php
if ($resource_code) {
    echo '<h1 class="resource_code">รหัสวิดีโอ : ' . $resource_code . '</h2>';
}
?>




<div class="grid_9">
    <div class="msg-no-login">

        <?php echo $login_form; ?>
    </div>
    <h1><?php echo $title; ?></h1>

    <fb:like href="<?php echo $facebook_url; ?>" send="true" width="880" show_faces="true"></fb:like>
    <fb:comments href="<?php echo $facebook_url; ?>" num_posts="25" width="880"></fb:comments>
</div>
</div>
<div class="grid_3">

</div>
<div class="clearfix"></div>

<style>
    .msg-no-login{
        background-color: #F1F1F1; 
        border: 3px solid #CECECE;
        display: block;
        height: 540px;text-align: center;
    }
    .msg-no-login span{
        color: #FFB2E4;
        clear: both;
        display: inline-block;
        margin-top: 30px;
        font-size: 24px;
    }
    .msg-no-login a{
        clear: both;
        display: block;
        margin-top: 30px;
        font-size: 14px;
    }
    
    .msg-no-login h1{
        width: 215px;
        margin: auto;
        text-align: center;
        padding: 10px 10px;
        border: 1px solid #CCCCCC;
        margin-top: 40px;
        font-size: 18px;

    }
    #center-box{
        margin: auto;

        width: 215px;
        padding: 20px 10px 0 10px;

        border-bottom: 1px solid #CCCCCC;
        border-left: 1px solid #CCCCCC;
        border-right: 1px solid #CCCCCC;
    }
    #btn-center{
        margin-top: 20px;
        margin-left: 120px;

    }
    #btn_submit{

    }
    #remember-me-label{
        width: 150px;
        text-align: left;
    }

</style>