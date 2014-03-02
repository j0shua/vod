<div class="container_12">
    <h1 class="main-title"><?php echo $title; ?></h1>
    <!--กระดาษคำตอบ-->

    <div class="grid_3" >
        <div id="send_answer_box">
            <form action="<?php echo $form_action; ?>" method="post" id="answer_sheet_form">

                <input type="hidden" value="<?php echo $ca_id; ?>" name="ca_id">
                <input class="btn-a"  id="btn_send_answer_sheet" type="button" value="ส่งข้อสอบ" onclick="submit_form();">


            </form>
        </div>
        <div id="clock_box">
            <div id="start_end_time">
                <?php echo $start_end_text; ?>
            </div>
            <div id="nomal_clock"></div>
            <div id="countdown_clock"></div>
        </div>
        <div id="answer_sheet_box">
        </div>
        <?php if ($ranking_data) { ?>
            <h2 class="head1"><?php echo $title_ranking; ?> </h2>
            <table border="1" class="data">
                <thead>
                    <tr>
                        <th>ลำดับ</th>
                        <th>ชื่อ</th>
                        <th>คะแนน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ranking_data as $k => $v) { ?>
                        <tr>
                            <td><?php echo $k + 1; ?></td>
                            <td><?php echo $v['user_data']['full_name']; ?></td>
                            <td><?php echo $v['get_score']; ?></td>

                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>


    </div>
    <!--สิ้นสุดกระดาษคำตอบ-->
    <!--ส่วนแสดงข้อสอบ-->

    <div class="grid_9" >
        <div id="question_box">Loading...</div>
    </div>
</div>
<!--สิ้นสุดส่วนแสดงข้อสอบ-->
<script>
    // timer
    var remaining_time = <?php echo $remaining_time ?>;
    var serverdate = new Date(<?php echo date('y,n,j,G,i,s'); ?>);
    var hour = serverdate.getHours();       // hour
    var minute = serverdate.getMinutes();     // minutes
    var secunde = serverdate.getSeconds();     // seconds
    $(function() {
        setInterval("update_clock()", 1000);
    });
    /**
     * นาฬิกา server
     * @returns {undefined}
     */
    function update_clock() {
        secunde++;
        update_remaining_time();
        if (secunde > 59) {
            secunde = 0;
            minute++;
        }
        if (minute > 59) {
            minute = 0;
            hour++;
        }
        if (hour > 23) {
            hour = 0;
        }
        $("#nomal_clock").html("เวลาเซิฟเวอร์ " + hour + ":" + minute + ":" + secunde);

    }
    /**
     * นาฬิกานับถอยหลัง
     * @returns {undefined}
     */
    function update_remaining_time() {
        remaining_time--;
        if (remaining_time < 1) {
            alert("หมดเวลาทำข้อสอบแล้ว ระบบจะทำการส่งข้อสอบทันที");
            force_submit_form();
        }
        var rd, rh, rm, ri;
        rd = Math.floor(remaining_time / 86400);
        rh = Math.floor((remaining_time % 86400) / 3600);
        rm = Math.floor((remaining_time % 3600) / 60);
        ri = remaining_time % 60;
        if (rd > 0) {
            rd = rd + " วัน";
        } else {
            rd = '';
        }
        if (rh > 0) {
            rh += ' ชั่วโมง ';
        } else {
            rh = '';
        }
        rm += ' นาที ';
        ri += ' วินาที ';

        $("#countdown_clock").html("เหลือเวลา " + rd + rh + rm + ri);
    }


// answer
    var answer_change = false;
    var sure_count = 0;
    var send_count = 0;


    $(function() {
        set_page_height();
        $(window).resize(function() {
            set_page_height();
        });
        q_goto(0, send_answer_box);
        //q_goto(0);

    });
    function set_page_height() {
        var windows_height = $(window).height() - 50;
        $("#main-wrapper").height(windows_height);
    }
    var send_answer_box = function() {
        var data = 'ca_id=' + ca_id;
        data += "&" + $("#question_box_form").serialize();

        $.ajax({
            type: "POST",
            url: ajax_send_answer_url,
            data: data,
            async: false,
            dataType: "json"

        }).done(function(json) {
            sure_count = json.sure_count;
            send_count = json.send_count;


        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        });

    };
    function q_goto(sheet_q_index, call_back) {
        var data = 'ca_id=' + ca_id
        data += '&sheet_q_index=' + sheet_q_index;
        $.ajax({
            type: "POST",
            url: ajax_get_question_url,
            data: data,
            dataType: "json",
            async: false,
            beforeSend: function() {
                $('body').showLoading();
                $('body').hideLoading();
            }
        }).done(function(json) {
            $("#question_box").html(json.render.question_box);
            $("#answer_sheet_box").html(json.render.answer_sheet);
            if (call_back) {
                call_back();
            }
        }).fail(function(jqXHR, textStatus) {
            alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
            console.log(jqXHR);
        });
        $('body').hideLoading();
        //เมื่อเปลี่ยนแปลง answer box

    }
    function submit_form() {
        if (answer_is_text) {
            if (answer_change) {
                send_answer_box();
            }
        }
        if (question_count === send_count) {
            if (sure_count === question_count) {

                if (confirm("คุณแน่ใจว่าจะส่งข้อสอบทันที่ใช่ไหม")) {
                    $("#answer_sheet_form").submit();
                }

            } else {
                if (confirm("คุณยังมีการตอบที่ยังไม่แน่ใจ\nคุณแน่ใจว่าจะส่งข้อสอบทันที่ใช่ไหม")) {
                    $("#answer_sheet_form").submit();
                }
            }
        } else {
            alert("โปรดทำข้อสอบให้เสร็จ");
        }
    }
    function force_submit_form() {
        $("#answer_sheet_form").submit();
    }
</script>
<style>
    /*
    ========== ส่วนของนาฬิกา
    */
    #start_end_time{
        border: solid 1px #BDBDBD; 
        margin-bottom: 10px;
        padding: 5px;
    }
    #nomal_clock{
        border: solid 1px #BDBDBD; 
        margin-bottom: 10px;
        padding: 5px;
    }
    #countdown_clock{
        border: solid 1px #BDBDBD; 
        padding: 5px;
    }
    /*
    ========== ส่วนฟอร์มส่งกระดาษคำตอบ
    */
    #btn_send_answer_sheet{
        width:100%;
    }
    /*
    ========== ส่วนกระดาษคำตอบ
    */    
    .answer_cell{
        text-align: center;

        display: inline-block;

        color: #000;
        font-size: 16px;
        border: solid 2px #ffffff;

    }
    .answer_cell a{
        display: block;
        width: 30px;
        border: solid 3px #E8E8E8;



    }
    .answer_cell.current_q a{
        border: solid 3px #029FEB;
    }

    .dosure a{
        background-color: #8CB82B;
        color: #ffffff;
    }
    .donotsure a{
        background-color: #FFD2D2;
        color: #000;
    }


    #send_answer_box{
        border: 1px solid #BDBDBD;
        margin-bottom: 10px;
        margin-top: 10px;
        padding: 10px;
    }
    #clock_box{
        border: 1px solid #BDBDBD;
        margin-bottom: 10px;
        margin-top: 10px;
        padding: 10px;
    }
    #answer_sheet_box{
        border: 1px solid #BDBDBD;
        margin-bottom: 10px;
        margin-top: 10px;
        padding: 5px;
    }
    /*
    ========== ส่วนแสดงโจทย์
    */
    #question_image_box {
        border: 1px solid #BDBDBD;
        border-bottom: none;

        padding: 10px;
    }
    #question_answer_box{
        border: 1px solid #BDBDBD;
        border-bottom: none;

        padding: 10px;
    }
    #question_nav_box{
        border: 1px solid #BDBDBD;
        /*        border-bottom: none;*/
        padding: 10px;
    }
    #question_sure_box{
        border: 1px solid #BDBDBD;
        padding: 10px;
        display: none;
    }
    #question_answer_box p{
        border: 1px solid #BDBDBD;
        margin-right: 8px;
        width: 100px;
        display: inline-block;
    }
    #question_answer_box p.p_close_test{
        border: none;

        margin-right: 8px;
        width: 100%;
        display: block;
    }
    #question_answer_box p.p_close_test label{
        text-align: right;
        width: auto;
        margin-left: 0px;

    }
    #question_answer_box input{
        margin-left: 5px;
        margin-bottom: 2px;
    }    
    #question_answer_box label{
        display: inline-block;
        width: 70px;
        margin-left: 5px;
    }

</style>