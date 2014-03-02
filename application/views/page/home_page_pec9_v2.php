<div class="top_10">
    <div class="grid_2 clearfix" id="front-left-side" >

        <?php if (!$is_login) { ?>
            <div><?php echo anchor('user/register', 'สมัครสมาชิก', 'target="_blank" class="front_reg_btn"'); ?></div>

            <div>    
                <form id="front-login-form" class="front_reg_from" method="post" action="<?php echo site_url("user/do_login"); ?>">
                    <h2>ลงชื่อเข้าใช้</h2>
                    <p>
                        <input type="text"  id="username" name="username" value="" placeholder="ยูสเซอร์เนม">
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
        <?php } ?>
        <div >

            <?php echo anchor('page/truemoney_topup', ' เติมเงิน', 'target="_blank" class="front_topup_btn"'); ?></div>



        <fb:like-box  href="https://www.facebook.com/134710609926624" width="180" height="630" show_faces="true" border_color="#E8E8E8" stream="true" header="false"></fb:like-box>

        <h2 class="head1">facebook</h2>
        <div class="front_box">
            <ul>
                <li><a target="_blank" href="https://www.facebook.com/ScienceHereHere">ScienceHereHere</a></li>
                <li><a target="_blank" href="https://www.facebook.com/groups/401362439920847">ครูภาษาอังกฤษ</a></li>
                <li><a target="_blank" href="https://www.facebook.com/groups/280499578716702/">ครูสอนคอม</a></li>
                <li><a target="_blank" href="https://www.facebook.com/groups/502116576468301/">ครูสอนฟิสิกส์</a></li>
                <li><a target="_blank" href="<?php echo base_url('files/images/logo-big.png'); ?>">Logo</a></li>

            </ul>
        </div>



    </div>

    <div class="grid_6" >
        <div id="banners" >



            <a href="<?php echo site_url("house/u/2/1") ?>"  target="_blank">
                <img src="<?php echo base_url("files/images/banner005.jpg"); ?>" alt="ชั่วโมงละ 26 บาท" />

            </a>
            <?php //if (!$is_login) { ?>
            <?php if (TRUE) { ?>
                <a href="<?php echo site_url("user/register") ?>"  target="_blank">
                    <img src="<?php echo base_url("files/images/banner004.jpg"); ?>" alt="ชั่วโมงละ 26 บาท" />

                </a>
            <?php } ?>
<!--            <a href="<?php echo site_url("house/u/2/1") ?>"  target="_blank">
<img src="<?php echo base_url("files/images/banner006.jpg"); ?>" alt="Physics Base" />
<span>
<strong>3 วันพร้อมสอบ ฟิสิกส์  เคมี O-NET</strong><br />
สรุปเนื้อหาสำหรับเตรียมสอบ O-NET แนวข้อสอบและแบบฝึกหัดพร้อมเฉลย หากไม่เข้าใจจุดไหนสามารถเข้าดูวิดีโอการสอนผ่านเว็บไซต์ได้ทันที อีกทั้งสามารถใช้งานได้ทั้งบน PC และ Tablet/Smart Phone ได้อีกด้วย
</span>
</a>-->
            <a href="https://www.facebook.com/notes/educasycom/557364107684844"  target="_blank">
                <img src="<?php echo base_url("files/images/banner010.jpg"); ?>" alt="Physics Base" />
                <span>
                    <strong>3 วันพร้อมสอบ ฟิสิกส์  เคมี O-NET</strong><br />
                    แถมคูปองเรียนออนไลน์ 260 บาท หนังสือราคาเล่มละ 199 
                </span>
            </a>
<!--            <a href="<?php echo site_url("house/u/2/1") ?>"  target="_blank">
                <img src="<?php echo base_url("files/images/banner001.jpg"); ?>" alt="Free 100 Minute" />
                <span>
                    <strong>3 วันพร้อมสอบฟิสิกส์ O-NET</strong><br />
                    สรุปเนื้อหาสำหรับเตรียมสอบ O-NET แนวข้อสอบและแบบฝึกหัดพร้อมเฉลย หากไม่เข้าใจจุดไหนสามารถเข้าดูวิดีโอการสอนผ่านเว็บไซต์ได้ทันที อีกทั้งสามารถใช้งานได้ทั้งบน PC และ Tablet/Smart Phone ได้อีกด้วย

                </span>
            </a>-->



        </div>
        <div class="main-menu-btn"  ><?php echo anchor('page/yt', 'คลิ๊กที่นี่ เพื่อ ทดลองเรียน ผ่าน วิดีโอออนไลน์ ฟรี !', 'target="_blank"'); ?></div> 
    </div>
    <div class="grid_4">
        <div class="fb-like" data-href="https://www.facebook.com/134710609926624" data-colorscheme="light" data-layout="standard" data-action="like" data-show-faces="false" data-send="false" style="width: 380px; background-color: none;"></div>



        <div class="" style="margin-top: 5px;">
            <a href="#"><img   title="" src="<?php echo base_url('files/images/course01.png'); ?>"></a>
        </div>
        <div class="main-menu-btn" style="color: #FF12DD;"  ><?php echo anchor('https://www.facebook.com/notes/educasycom/557364107684844', 'คลิ๊กสั่งซื้อหนังสือ !', 'target="_blank" style="font-size: 34px;"'); ?></div>

    </div>

    <div class="grid_6">

        <h2 class="head1" style="padding: 5px 0; text-indent: 10px; font-size: 24px; background-color: #C3B5FF;" >
            <img src="<?php echo base_url('files/images/pc-32.png'); ?>"/>

            เข้าเรียนกันได้เลย !

        </h2>
        <div class="clearfix" style="background-color: white; margin-bottom: 5px;">
            <div style="float: left;width: 160px;" class="clearfix" >
                <img   title="รูปประจำตัว" src="<?php echo base_url('files/images/pec9_front_play.png'); ?>">
            </div>
            <h2 style="padding-bottom: 8px; border-bottom: 4px solid #F2F2F2; margin-left: 10px;margin-top: 10px; display: block;width: 400px;float: left;clear: right;">
                <a style="font-size: 24px;color: #ee1eb5;" href="<?php echo site_url('house/u/2/1'); ?>">3 วันพร้อมสอบฟิสิกส์ O-NET </a>
            </h2>
            <div style="width: 400px;color: #0272a7;font-weight: bold;margin-top: 5px;  float: left; margin-left: 10px; display: block;">ผู้สอน : อ.ประสิทธิ์ จันต๊ะภา</div>
            <div style="width: 400px;color: #45484F;margin-top: 5px;  float: left; margin-left: 10px; display: block;">3 วันพร้อมสอบฟิสิกส์ O-NET  น้องๆ สามารถติวเนื้อหาวิชาฟิสิกส์ ฉบับ โอเน็ตได้อย่างรวดเร็วและง่ายดายเพียง 3 วันกับวิด๊โอติว ชุดนี้ อีกทั้งยังมีหนังสือคู่มือประกอบการติว ที่สามารถสั่งซื้อได้ทางเว็บไซต์ได้แล้ววันนี้ คิดค่าเรียนเพียง 26 บาทต่อชั่วโมง โดยคิดเป็นวินาที</div>


        </div>
        <div class="clearfix" style="background-color: white; margin-bottom: 5px;">
            <div style="float: left;width: 160px;" class="clearfix" >
                <img   title="รูปประจำตัว" src="<?php echo base_url('files/images/pec9_front_play.png'); ?>">
            </div>
            <h2 style="padding-bottom: 8px; border-bottom: 4px solid #F2F2F2; margin-left: 10px;margin-top: 10px; display: block;width: 400px;float: left;clear: right;">
                <a style="font-size: 24px;color: #ee1eb5;" href="<?php echo site_url('house/u/2/70'); ?>">3 วันพร้อมสอบคณิตศาสตร์ O-NET </a>
            </h2>
            <div style="width: 400px;color: #0272a7;font-weight: bold;margin-top: 5px;  float: left; margin-left: 10px; display: block;">ผู้สอน : อ.ประสิทธิ์ จันต๊ะภา</div>
            <div style="width: 400px;color: #45484F;margin-top: 5px;  float: left; margin-left: 10px; display: block;">3 วันพร้อมสอบคณิตศาสตร์ O-NET  น้องๆ สามารถติวเนื้อหาวิชาฟิสิกส์ ฉบับ โอเน็ตได้อย่างรวดเร็วและง่ายดายเพียง 3 วันกับวิด๊โอติว ชุดนี้ อีกทั้งยังมีหนังสือคู่มือประกอบการติว ที่สามารถสั่งซื้อได้ทางเว็บไซต์ได้แล้ววันนี้ คิดค่าเรียนเพียง 26 บาทต่อชั่วโมง โดยคิดเป็นวินาที</div>


        </div>





        <div>
            <div id="facebook_logo" style="height: 55px;">
                <img src="<?php echo base_url("files/images/facebook_logo.png"); ?>" alt="board" />
            </div>
            <div class="fb-comments" data-href="http://www.pec9.com/" data-width="580" data-numposts="20" data-colorscheme="light"></div>
        </div>
    </div>

    <div class="grid_4">


        <div class="book-promo clearfix">
            <h1>หนังสือ 3 วันพร้อมสอบเคมี O-NET</h1>
            <a href="<?php echo site_url('house/u/2'); ?>"><img src="<?php echo base_url('files/images/3days-physics-cover.jpg'); ?>"></a>

            <p>
                หนังสือ 3 วันพร้อมสอบเคมี O-NET สรุปเนื้อหาสำหรับเตรียมสอบ O-NET 
                แนวข้อสอบและแบบฝึกหัดพร้อมเฉลย รวมทั้งหมด 210 หน้า
                ราคาเล่มละ 199 บาท
                ซื้อตอนนี้ยังรับฟรี คูปองเติมเงินเรียนออนไลน์ มูลค่า 260 บาทพรี
                คุ้มสุดคุ้มสั่งซื้อผ่าน facebook <a href="https://www.facebook.com/notes/educasycom/557364107684844" target="_blank">www.facebook.com</a> 
                <a href="<?php echo site_url('files/chem-demo.pdf'); ?>" target="_blank" class="btn-a">คลิ๊กดูตัวอย่างหนังสือ</a>
            </p>
        </div>
        <h2 class="head1" style="padding: 5px 0; text-indent: 10px; font-size: 24px; background-color: #C3B5FF;" >
            เข้าเรียนในขณะนี้
        </h2>
        <div><iframe style="width: 380px;overflow-x: hidden;height: 395px;" src="<?php echo site_url('play/play_info/user_online/380'); ?>" ></iframe>    </div>
    </div>

</div>
<div class="pagefooter">
    <div id="chat_btn">ซ่อนกล่องแชท</div>
    <div class="chatwing_div" id="chatwing-embedded-e13b596c-d829-45bd-b794-2e71721d8a28"></div>


</div>
<script>
    var chat_hide = false;


    (function(d) {
        var cwjs, id = 'chatwing-js';
        if (d.getElementById(id)) {
            return;
        }
        cwjs = d.createElement('script');
        cwjs.type = 'text/javascript';
        cwjs.async = true;
        cwjs.id = id
        cwjs.src = "//chatwing.com/code/e13b596c-d829-45bd-b794-2e71721d8a28/embedded";
        d.getElementsByTagName('head')[0].appendChild(cwjs);
    })(document);


    $(function() {
        $("#chat_btn").click(function() {
            if (chat_hide == false) {
                $(this).html('เปิดกล่องแชท');
                $(".pagefooter").css("height", "40px");
                chat_hide = true;
            } else {
                $(this).html('ปิดกล่องแชท');
                $(".pagefooter").css("height", "413px");
                chat_hide = false;
            }

        }).click();
        $('#banners').coinslider({
            hoverPause: true,
            width: 580,
            height: 260,
            delay: 5000
        });

        $("#front-login-form").submit(function() {
            $("#username").val($.trim($("#username").val()));
            if ($("#username").val() === '' || $("#password").val() == '') {
                return false;
            }
            return true;
        });

    });

//    window.fbAsyncInit = function() {
//
//        FB.Event.subscribe('edge.create', function(href, widget) {
//            var data = 'uid=' + uid;
//            $.ajax({
//                type: "POST",
//                url: ajax_fb_like_url,
//                data: data,
//                dataType: "json",
//                async: false
//            }).done(function(json) {
//                if (json.success) {
//                    alert(json.message);
//                }
//
//            }).fail(function(jqXHR, textStatus) {
//
//            }).always(function() {
//
//            });
//        });
//        FB.Event.subscribe('edge.remove', function(href, widget) {
//        });
//    };

</script>
<style>
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
        background-color:#3C3837;
        -moz-box-shadow: 0px 10px 14px -7px #969696;
        -webkit-box-shadow: 0px 10px 14px -7px #969696;
        box-shadow: 0px 10px 14px -7px #969696;
        border:1px solid #635450;
        display:block;
        color:#E27C32;
        font-family:arial;
        font-size:20px;
        font-weight:bold;
        padding:6px 18px;
        text-decoration:none;
        /*        text-shadow:0px 3px 0px #c70067;*/
        text-align: center;
        margin-bottom: 5px;

        -webkit-border-radius: 8px;
        -moz-border-radius: 8px;
        border-radius: 8px;
        margin-top: 10px;
    }
    .main-menu-btn.prefix_img a{
        text-align: left;
        padding:6px 5px;
    }
    .main-menu-btn a:hover{
        background-color:#66504B;
    }
    .fb-btn a{
        background-color: #3B5998;
        border:1px solid #1F4189;
    }
    .fb-btn a:hover{
        background-color: #466DBA;
        border:1px solid #1F4189;
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
        height: 295px;
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


    .pagefooter
    {

        height:413px;

        position: fixed;
        bottom:0;

        z-index:99;
        right: 0px;
    }
    #chat_btn{
        font-weight: bold;
        padding: 8px;
        font-size: 18px;
        display: block;
        text-align: center;
        background-color: #014DA5;
        color: #F2F2F2;
        cursor: pointer;
    }
    #chat_btn:hover{

        background-color: #3C81D1;

    }
</style>