<div class="top_10">
    <div class="grid_2 clearfix" id="front-left-side" style="height: 600px;" >

        <?php if (!$is_login) { ?>


            <div id="front-login-form-wrapper" class="alpha omega">
                <form id="front-login-form" method="post" action="<?php echo site_url("user/do_login"); ?>">
                    <h2 class="head1">ลงชื่อเข้าใช้</h2>
                    <p>
                        <input type="text"  id="username" name="username" value="" placeholder="อีเมล์">
                    </p>
                    <p>
                        <input type="password"  id="password" name="password"  value="" placeholder="รหัสผ่าน">
                    </p>
                    <p>
                        <input type="checkbox" name="remember_me" value="ON" />
                        <label for="remember_me" id="remember-me-label">จำฉันเอาไว้</label>
                    </p>
                    <p class="clearfix">
                        <input type="submit" class="clearfix btn-a " id="btn_front_page_login" name="submit" value="ลงชื่อเข้าใช้ระบบ" >
                    </p>
                    <a style="margin-left: auto; margin-right: auto; clear: both; width: auto;display: block;margin-top: 5px;"  href="<?php echo site_url('user/forget_pass'); ?>" class="">ลืมรหัสผ่าน</a>
                </form>
            </div>
            <a href="<?php echo site_url('user/registerteacher'); ?>" class="link-img"><img src="<?php echo base_url('files/images/pk_register_teacher.png'); ?>" alt="student register"></a>
            <a href="<?php echo site_url('user/register'); ?>" class="link-img"><img src="<?php echo base_url('files/images/pk_register_student.png'); ?>" alt="student register"></a>

        <?php } ?>
        <img src="<?php echo base_url('files/images/pk_callcenter.png'); ?>" alt="ติดต่อเจ้าหน้าที่">
        <h2 class="head1" style="margin-top: 10px; height: 55px;" >ดาวน์โหลด <br>เครื่องมือจำเป็น</h2>
        <div class="front_box download_box" style="line-height: 20px;font-weight: bold;">
            <?php
            if (!$is_android) {
                if ($download_browser) {
                    ?>
                    <div >
                        <a target="_blank" href="http://www.mozilla.org/th/firefox/fx/" class="link-img"><img src="<?php echo base_url('files/images/firefox_download_small.png'); ?>" alt="Download Firefox"><span>firefox</span></a>
                    </div>
                    <div >
                        <a target="_blank" href="http://www.google.com/intl/th/chrome/browser/" class="link-img"><img src="<?php echo base_url('files/images/chrome_download_small.png') ?>" alt="Download Google Chrome"><span>Chrome</span></a>
                    </div>

                    <?php
                }
            }
            ?>

            <?php if ($flash_download_url) { ?>
                <div>
                    <a target="_blank" href="<?php echo $flash_download_url; ?>" class="link-img"><img src="<?php echo base_url('files/images/flash_download_small.png'); ?>" alt="Download Flash Player"><span>Flash Player</span></a>
                </div>
                <div>
                    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.zxing.client.android&hl=th" class="link-img"><img src="<?php echo base_url('files/images/qrscan_download_small.png'); ?>" alt="Download barcode scaner"><span>barcode scaner</span></a>
                </div>

            <?php } else { ?>
                <div >
                    <a target="_blank" href="http://get2.adobe.com/flashplayer/" class="link-img"><img src="<?php echo base_url('files/images/flash_download_small.png'); ?>" alt="Download Flash Player"><span>Flash Player</span></a>
                </div>
            <?php } ?>
            <?php if (TRUE) { ?>
                <div >
                    <a target="_blank" href="<?php echo $acrobat_url; ?>" class="link-img"><img src="<?php echo base_url('files/images/acrobat_download_small.png'); ?>" alt="Download Acrobat"><span>Acrobat Reader</span></a>
                </div>
            <?php } ?>
        </div>

    </div>
    <div class="grid_10" >
        <div id="fp-tabs1">
            <ul>
                <li><a href="#fptab-1"><span>เนื้อหาเข้ามาใหม่</span></a></li>
                <li><a href="#fptab-2"><span>ประถมศึกษา</span></a></li>
                <li><a href="#fptab-3"><span>มัธยมศึกษาตอนต้น</span></a></li>
                <li><a href="#fptab-4"><span>มัธยมศึกษาตอนปลาย</span></a></li>
                <li><a href="#fptab-5"><span>ทั่วไป </span></a></li>
            </ul>
            <div id="fptab-1" class="fptab">
                <a href="<?php echo site_url('house/u/2/57'); ?>">ติวสบาย ฟิสิกส์ ม.4 - ม.6 ( เพิ่มเติม ) (link ตัวอย่าง )</a>
            </div>
            <div id="fptab-2" class="fptab">
                <a href="<?php echo site_url('house/u/2/57'); ?>"> ประถมศึกษา (link ตัวอย่าง )</a>
            </div>
            <div id="fptab-3" class="fptab">
                <a href="<?php echo site_url('house/u/2/57'); ?>"> มัธยมศึกษาตอนต้น (link ตัวอย่าง )</a>
            </div>
            <div id="fptab-4" class="fptab">
                <a href="<?php echo site_url('house/u/2/57'); ?>"> มัธยมศึกษาตอนปลาย   (link ตัวอย่าง )</a>
            </div>
            <div id="fptab-5" class="fptab">
                <a href="<?php echo site_url('house/u/2/57'); ?>"> ทั่วไป   (link ตัวอย่าง )</a>
            </div>

        </div>
    </div>


    <div class="grid_10">

        <h2 id="recommend-bar" class="head1" style="padding: 5px 0; text-indent: 10px; font-size:34px; height: 40px;background-color: #C3B5FF;" >
            <img src="<?php echo base_url('files/images/recommend-girl.png'); ?>"/>

            แนะนำคุณครู

        </h2>

        <form style="margin-left: 90px;" action="<?php echo $seach_teacher_form_action ?>" method="get" >
            <?php echo form_dropdown('qtype', $qtype_options, $default_qtype, 'id="qtype"'); ?>
            <input id="search_t" type="text" name="t" value="" />
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
                            <?php echo ($cell['school_name']) ? '<p>' . anchor('search/teacher?qtype=school_name&t=' . $cell['school_name'], $cell['school_name']) . '</p>' : ''; ?>

                        </div>



                    </li>


                    <?php
                }
                ?>
            </ul>
        </div>


    </div>

</div>

<div class='ajax' style='display:none'><a href="http://www.youtube.com/embed/VOJyrQa_WR4?rel=0&autoplay=1amp;wmode=transparent" title="Homer Defined">Outside HTML (Ajax)</a></div>
<script>
    $(function() {
        $("#fp-tabs1").tabs();

        $("#front-login-form").submit(function() {
            $("#username").val($.trim($("#username").val()));
            if ($("#username").val() === '' || $("#password").val() === '') {
                return false;
            }
            return true;
        });
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
<?php // if (!$is_login) { 
    if (FALSE) { 
    ?>
            $.colorbox({href: 'http://www.youtube.com/embed/t109bJQAEUY?rel=0&autoplay=1&wmode=transparent', opacity: 0.2, title: "วิดีโอแนะนำโปรครู", iframe: true, open: true, width: 650, height: 560})
<?php } ?>



    });









</script>
<style>
    #fp-tabs1{
        border: none;
    }
    #fp-tabs1.ui-tabs .ui-tabs-nav li{
        -moz-border-radius-topleft: 8px;
        -webkit-border-top-left-radius: 8px;
        border-top-left-radius: 8px;
        -moz-border-radius-topright: 8px;
        -webkit-border-top-right-radius: 8px;
        border-top-right-radius: 8px;
        border: none;
    }

    #fp-tabs1.ui-tabs .ui-tabs-panel{
        border-width: 1px;
    }

    .fptab{
        height: 243px;
        border: solid 1px #DAF0FA;
    }
    #fp-tabs1 .ui-widget-header{
        border: none;
        border-bottom: solid 4px #DAF0FA;
        background: none;
    }
    .download_box img{
        margin-right: 10px;
    }
    .download_box span{
        line-height: 40px;
    }

    .book-promo{
        display: block;
        padding: 10px;


        margin-bottom: 10px;
        /*        height: 249px;*/


        background: linear-gradient(to bottom,  rgba(224,243,250,1) 0%,rgba(216,240,252,1) 50%,rgba(184,226,246,1) 51%,rgba(182,223,253,1) 100%); /* W3C */


    }
    .book-promo h1{
        color: #480091;
        background: none;
        border-bottom: 1px solid #CCCCCC;
        padding: 0px 10px 10px 10px;
        text-shadow: 0 1px 0 #FFFFFF;
        font-size: 24px;
        line-height: 24px;
        margin-bottom: 5px;
        display: block;
        font-family: thai_sans_literegular;
    }

    .book-promo p{

        display: block;



        color: #45484F;
        text-overflow: ellipsis;
        font-size: 13px;

    }
    .book-promo img{

        background-color: #ffffff;
        display: block;
        float: left;
        border: solid 1px #CCCCCC;
        padding: 0px;
        border-radius: 2px;
        margin-right: 5px;


    }

    .main-menu-btn a{
        background-color:#ff5bb0;
        border:1px solid #ee1eb5;
        display:block;
        color:#ffffff;
        font-family:arial;
        font-size:20px;
        font-weight:bold;
        padding:6px 18px;
        text-decoration:none;
        /*        text-shadow:0px 3px 0px #c70067;*/
        text-align: center;
        margin-bottom: 5px;
    }
    .main-menu-btn.prefix_img a{
        text-align: left;
        padding:6px 5px;
    }
    .main-menu-btn a:hover{
        background-color:#ef027d;
    }



    #front-login-form p{
        margin-bottom: 5px;
    }
    #front-login-form input[type="text"], #front-login-form input[type="password"]{
        width: 95%;
        font-size: 18px;

    }
    #front-login-form input[type="submit"]{
        width: 122px;
        font-size: 12px;

    }


    .front_box{
        margin-bottom: 10px;
    }
    .front_box ul{
        margin-left: 20px;
    }
    .front_box li{
        margin-left: 0px;
        list-style: disc;
    }

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


    .fb_iframe_widget{
        background-color: #ffffff;
    }

</style>