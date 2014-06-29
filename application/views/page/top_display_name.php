<div id="top-display-name" class="clearfix">
    <img  class="clearfix" src="<?php echo site_url('ztatic/img_avatar_32/' . $uid); ?>">
    <span><?php echo $display_name; ?></span></div>
<style>
    #top-display-name{
        float: left;
        display: block;
        padding-top: 10px;
        padding-right: 10px;
        color: #ffffff;
    }
    #top-display-name img{

        display: block;
        float: left;
    }
    #top-display-name span{
        line-height: 32px;
        margin-left: 10px;
        display: block;
        float: left;
        font-size: 16px;
    }
    #top-display-name a{
        color: #ffffff;
    }
</style>