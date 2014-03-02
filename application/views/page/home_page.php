<div class="top_10">
    <div class="grid_2 clearfix" id="front-left-side" >
        <?php if (!$is_login) { ?>
            <div id="front-login-form-wrapper">    
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
                        <input type="submit" class="clearfix btn-a" id="btn_submit" name="submit" value="ลงชื่อเข้าใช้ระบบ" >
                    </p>
                    <a style="clear: both; width: 100px;display: block;margin-top: 5px;"  href="<?php echo site_url('user/forget_pass'); ?>" class="">ลืมรหัสผ่าน</a>



                </form>
            </div>

            <div class="main-menu-btn"  ><?php echo anchor('user/register', 'ลงทะเบียนฟรี', 'target="_blank"'); ?></div>
        <?php } ?>
        <div class="main-menu-btn"  ><?php echo anchor('page/truemoney_topup', 'เติมเงิน', 'target="_blank"'); ?></div>

        <h2 class="head1" >เครื่องมือจำเป็น</h2>
        <div class="front_box">
            <?php
            if (!$is_android) {
                if ($download_browser) {
                    ?>
                    <div >
                        <a target="_blank" href="http://www.google.com/intl/th/chrome/browser/" class="link-img"><img src="<?php echo base_url('files/images/chrome_download.png') ?>" alt="Download Google Chrome"></a>
                    </div>
                    <div >
                        <a target="_blank" href="http://www.mozilla.org/th/firefox/fx/" class="link-img"><img src="<?php echo base_url('files/images/firefox_download.png'); ?>" alt="Download Firefox"></a>
                    </div> 
                    <?php
                }
            }
            ?>

            <?php if ($flash_download_url) { ?>
                <div>
                    <a target="_blank" href="<?php echo $flash_download_url; ?>" class="link-img"><img src="<?php echo base_url('files/images/flash_download.png'); ?>" alt="Download Flash Player"></a>
                </div>
                <div>
                    <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.zxing.client.android&hl=th" class="link-img"><img src="<?php echo base_url('files/images/qrscan_download.png'); ?>" alt="Download Flash Player"></a>
                </div>

            <?php } else { ?>
                <div >
                    <a target="_blank" href="http://get2.adobe.com/flashplayer/" class="link-img"><img src="<?php echo base_url('files/images/flash_download.png'); ?>" alt="Download Flash Player"></a>
                </div>
            <?php } ?>
            <?php if (TRUE) { ?>
                <div >
                    <a target="_blank" href="<?php echo $acrobat_url; ?>" class="link-img"><img src="<?php echo base_url('files/images/acrobat_download.png'); ?>" alt="Download Acrobat"></a>
                </div>
            <?php } ?>
        </div>
        <h2 class="head1">facebook</h2>
        <div class="front_box">
            <ul>
                <li><a target="_blank" href="https://www.facebook.com/ScienceHereHere">ScienceHereHere</a></li>
                <li><a target="_blank" href="https://www.facebook.com/groups/401362439920847">ครูภาษาอังกฤษ</a></li>
                <li><a target="_blank" href="https://www.facebook.com/groups/280499578716702/">ครูสอนคอม</a></li>
                <li><a target="_blank" href="https://www.facebook.com/groups/502116576468301/">ครูสอนฟิสิกส์</a></li>

            </ul>
        </div>

        <h2 class="head1">น่าสนใจ</h2>

        <div class="front_box" >
            <ul>
                <li><a target="_blank" href=" http://jusci.net/">jusci.net</a></li>
            </ul>
        </div>


    </div>

    <div class="grid_6" >
        <div id="banners" >
            <?php if (!$is_login) { ?>
                <a href="<?php echo site_url("user/register") ?>"  target="_blank">
                    <img src="<?php echo base_url("files/images/banner004.jpg"); ?>" alt="ชั่วโมงละ 26 บาท" />
                    <span>
                        <strong>ลงทะเบียน</strong><br />
                        ลงทะเบียน แล้วเติมเงิน จากนั้นเข้าเรียนได้ทันทีทุกที่ทุกวิชา ไม่มีจำกัดเวลา เพียงชั่วโมงละ 26 บาทเท่านั้น

                    </span>
                </a>
            <?php } ?>
            <a href="<?php echo site_url("house/u/2/1") ?>"  target="_blank">
                <img src="<?php echo base_url("files/images/banner001.png"); ?>" alt="Free 100 Minute" />
                <span>
                    <strong>3 พร้อมสอบฟิสิกส์ O-NET</strong><br />
                    สรุปเนื้อหาสำหรับเตรียมสอบ O-NET แนวนข้อสอบและแบบฝึกหัดพร้อมเฉลย หากไม่เข้าใจจุดไหนสามารถเข้าดูวิดีโอการสอนผ่านเว็บไซต์ได้ทันที อีกทั้งสามารถใช้งานได้ทั้งบน PC และ Tablet/Smart Phone ได้อีกด้วย

                </span>
            </a>
            <a href="<?php echo site_url("house/u/2/1") ?>"  target="_blank">
                <img src="<?php echo base_url("files/images/banner002.jpg"); ?>" alt="Physics Base" />
                <span>
                    <strong>3 พร้อมสอบคณิตศาสตร์ O-NET</strong><br />
                    สรุปเนื้อหาสำหรับเตรียมสอบ O-NET แนวนข้อสอบและแบบฝึกหัดพร้อมเฉลย หากไม่เข้าใจจุดไหนสามารถเข้าดูวิดีโอการสอนผ่านเว็บไซต์ได้ทันที อีกทั้งสามารถใช้งานได้ทั้งบน PC และ Tablet/Smart Phone ได้อีกด้วย
                </span>
            </a>

        </div>

    </div>
    <div class="grid_4">
        <div class="book-promo clearfix">
            <h1>หนังสือ 3 พร้อมสอบฟิสิกส์ O-NET</h1>
            <a href="<?php echo site_url('house/u/2'); ?>"><img src="<?php echo base_url('files/images/3days-physics-cover.jpg'); ?>"></a>

            <p>
                หนังสือ 3 พร้อมสอบฟิสิกส์ O-NET สรุปเนื้อหาสำหรับเตรียมสอบ O-NET แนวนข้อสอบและแบบฝึกหัดพร้อมเฉลย หากไม่เข้าใจจุดไหนสามารถเข้าดูวิดีโอการสอนผ่านเว็บไซต์ได้ทันที อีกทั้งสามารถใช้งานได้ทั้งบน PC และ Tablet/Smart Phone ได้อีกด้วย
                สามารถสั่งซื้อผ่าน facebook ได้ที่ <a href="https://www.facebook.com/messages/Educasy" target="_blank">www.facebook.com/messages/Educasy</a> 
            </p>
        </div>
    </div>

    <div class="grid_6">

        <h2 class="head1" style="padding: 5px 0; text-indent: 10px; font-size: 24px;" >ห้องเรียน</h2>
        <form action="<?php $seach_teacher_form_action ?>" method="get" >
            <input type="text" name="t" value="" />
            <input type="submit" value="ค้นหา" class="btn-a-small"/>
        </form>
        <div class="clearfix">
            <ul>
                <li class="house_teacher">
                    <div  class="avatar-img clearfix">  <img  class="clearfix"  title="รูปประจำตัว" src="<?php echo site_url('ztatic/img_avatar_128/2'); ?>"></div>

                    <div class="teacher_name">

                        <?php echo anchor('house/u/2', 'ครูประสิทธิ์ จันต๊ะภา'); ?>
                    </div>

                    <ul>
                        <li class="house_book"><?php echo anchor('house/u/2/1', '3 พร้อมสอบฟิสิกส์ O-NET'); ?></li>

                    </ul>
                </li>

                <li class="house_teacher even">
                    <div  class="avatar-img clearfix">  <img  class="clearfix"  title="รูปประจำตัว" src="<?php echo site_url('ztatic/img_avatar_128/13'); ?>"></div>

                    <div class="teacher_name">

                        <?php echo anchor('house/u/2', 'นันท์นภัส ฟักทอง'); ?></div>
                    <ul>
                        <li class="house_book"><?php echo anchor('house/u/2/1', '3 พร้อมสอบชีววิทยา O-NET'); ?></li>

                    </ul>
                </li>

            </ul>
        </div>

    </div>

    <div class="grid_4">
        <fb:like-box  href="https://www.facebook.com/Educasy" width="380" height="630" show_faces="true" border_color="#E8E8E8" stream="true" header="false"></fb:like-box>
    </div>
</div>
<script>
    $(function() {
        $('#banners').coinslider({
            hoverPause: true,
            width: 580,
            height: 260,
            delay: 40000
        });

        $("#front-login-form").submit(function() {
            $("#username").val($.trim($("#username").val()));
            if ($("#username").val() === '' || $("#password").val() === '') {
                return false;
            }
            return true;
        });

    });
</script>

<style>
    .book-promo{
        display: block;
        padding: 10px;


        margin-bottom: 10px;
        height: 249px;


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
        padding: 0px 10px 0px 4px;
        display: inline-block;
        float: left;
        width: 200px;

        color: #45484F;
        text-overflow: ellipsis;
        font-size: 13px;

    }
    .book-promo img{

        background-color: #ffffff;
        display: block;
        float: left;
        border: solid 1px #CCCCCC;
        padding: 5px;
        border-radius: 2px;


    }

    .main-menu-btn a{
        background-color:#ff5bb0;
        border:1px solid #ee1eb5;
        display:block;
        color:#ffffff;
        font-family:arial;
        font-size:22px;
        font-weight:bold;
        padding:6px 18px;
        text-decoration:none;
        /*        text-shadow:0px 3px 0px #c70067;*/
        text-align: center;
        margin-bottom: 5px;
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

    #front-login-form-wrapper{
        /*        border: 1px solid #CCCCCC;*/
        padding: 5px;
        margin-bottom: 10px;
        height: 190px;


        background: linear-gradient(to bottom,  rgba(224,243,250,1) 0%,rgba(216,240,252,1) 50%,rgba(184,226,246,1) 51%,rgba(182,223,253,1) 100%); /* W3C */

    }
    .front_box{
        margin-bottom: 10px;
    }
    .front_box ul{
        margin-left: 10px;
    }
    .front_box li{
        list-style: disc;
    }

    .house_teacher {
        float: left;
        width: 46%;
        border: solid 1px #CCCCCC;
        padding: 8px;
        font-family: thai_sans_literegular;
        font-size: 18px;
        font-weight: bold;
        color: #0272a7;
        border-radius: 2px;
        background-color: #FFFFFF;
        margin-bottom: 10px;
        height: 175px;
    }

    .house_teacher.even{
        float: right;

    }
    .house_teacher ul{
        margin-bottom: 5px;
        margin-top: 10px;
        display: block;
        clear: both;
    }

    li.house_teacher{
        list-style: none;
        margin-left: 0px;
    }
    .house_teacher a{
        font-size: 20px;

    }
    .house_teacher a:hover{
        text-decoration: underline;

    }
    .house_book{
        clear: both;
        margin-left: 5px;

    }

    .house_book a{
        font-size: 14px;
        font-family: tahoma;
        font-weight: normal;
        color: #029FEB;

    }

    .avatar-img{
        width: 128px;
        height: 128px;
        background-color: #ffffff;
        display: block;
        float: left;
        border: solid 1px #CCCCCC;
        padding: 5px;
        border-radius: 2px;
    }
    .house_teacher .teacher_name{
        margin-top: 10px;
        margin-left: 5px;
        width: 120px;
        float: left;
        word-wrap: break-word;
        text-align: left;
    }

    .fb_iframe_widget{
        background-color: #ffffff;
    }



</style>