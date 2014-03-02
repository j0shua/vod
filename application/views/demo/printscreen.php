<script>
    $(function() {
//        $(window).blur(function() {
//            $("#main-wrapper").hide();
//            console.log("blur");
//        });
//        $(window).focus(function() {
//            console.log("blur");
//            $("#main-wrapper").show();
//        });
        $(window).bind('keyup keydown', function(e) {
           // console.log(e);
            var c = e.keyCode || e.charCode;
            //if (c == 44)
              //  alert("print screen");
            // $("#main-wrapper").hide();
        });
        window.onbeforeprint = function() {
            console.log('This will be called before the user prints.');
        };
        window.onafterprint = function() {
            console.log('This will be called after the user prints');
        };

    });

</script>
<h1>Lorem Ipsum</h1>
<div class="grid_12">
    <p>Lorem Ipsum คือ เนื้อหาจำลองแบบเรียบๆ ที่ใช้กันในธุรกิจงานพิมพ์หรืองานเรียงพิมพ์ มันได้กลายมาเป็นเนื้อหาจำลองมาตรฐานของธุรกิจดังกล่าวมาตั้งแต่ศตวรรษที่ 16 เมื่อเครื่องพิมพ์โนเนมเครื่องหนึ่งนำรางตัวพิมพ์มาสลับสับตำแหน่งตัวอักษรเพื่อทำหนังสือตัวอย่าง Lorem Ipsum อยู่ยงคงกระพันมาไม่ใช่แค่เพียงห้าศตวรรษ แต่อยู่มาจนถึงยุคที่พลิกโฉมเข้าสู่งานเรียงพิมพ์ด้วยวิธีทางอิเล็กทรอนิกส์ และยังคงสภาพเดิมไว้อย่างไม่มีการเปลี่ยนแปลง มันได้รับความนิยมมากขึ้นในยุค ค.ศ. 1960 เมื่อแผ่น Letraset วางจำหน่ายโดยมีข้อความบนนั้นเป็น Lorem Ipsum และล่าสุดกว่านั้น คือเมื่อซอฟท์แวร์การทำสื่อสิ่งพิมพ์ (Desktop Publishing) อย่าง Aldus PageMaker ได้รวมเอา Lorem Ipsum เวอร์ชั่นต่างๆ เข้าไว้ในซอฟท์แวร์ด้วย</p>
</div>