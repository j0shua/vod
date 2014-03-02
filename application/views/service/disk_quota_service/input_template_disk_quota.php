

<h1><?php echo $title; ?></h1>


<div class="grid_5">
    <form id="main_form" class="normal-form" action="<?php echo $form_action; ?>" method="post" >
        <input type="hidden" name="data[dqt_id]" value="<?php echo $form_data['dqt_id']; ?>" />
        <p>
            <label for="title" >ชื่อแม่แบบ </label>
            <input type="text" id="title" name="data[title]" value="<?php echo $form_data['title']; ?>" >
        </p>
        <p>
            <label for="price" >ค่าบริการ </label>
            <input type="text" id="price" name="data[price]" value="<?php echo $form_data['price']; ?>">
        </p>
        <p>
            <label for="value_mb" >พื้นที่ MB </label>
            <input type="text" id="value" name="data[value_mb]" value="<?php echo $form_data['value_mb']; ?>" >
        </p>
        <p>
            <label for="days" >จำนวนวัน </label>
            <input type="text" id="days" name="data[days]" value="<?php echo $form_data['days']; ?>" >
        </p>
        <p>
            <label for="desc" >รายละเอียด </label>
            <textarea id="desc" name="data[desc]" rows="4" cols="20"><?php echo $form_data['desc']; ?></textarea>

        </p>




        <input type="submit" value="บันทึกข้อมูล" id="btnSubmit" class="btn-a" >

        <a href="<?php echo $cancel_link; ?>" class="btn-a">ยกเลิก</a>

    </form>

</div>


<script>
    $(function() {
        $("#price,#value_mb,#days").typeonly("0123456789");

        $("#main_form").submit(function() {
            error_msg = ["== โปรดกรอก =="];
            var bvalid = true;
            $("#title").val($.trim($("#title").val()));
            if ($("#title").val() == '') {
                bvalid = false;
                error_msg.push("ชื่อแม่แบบ");
            }
            if ($("#price").val() == '') {
                bvalid = false;
                error_msg.push("ค่าบริการ");
            }
            if ($("#value").val() == '') {
                bvalid = false;
                error_msg.push("ขนาดพื้นที่");
            } else if ($("#value").val() < 1) {
                bvalid = false;
                error_msg.push("ขนาดพื้นที่");
            }
            if ($("#days").val() == '') {
                bvalid = false;
                error_msg.push("จำนวนวัน");
            }
            if(!bvalid){
                alert(error_msg.join("\n"));
            }
            
              



            return bvalid;
        });
    });

</script>







