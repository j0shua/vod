<li class="menu"><a href="<?php echo site_url(); ?>" class="drop">หน้าแรก</a></li>
<?php if ($is_login) { ?>
    <li class="menu"><a href="#" class="drop">เมนูหลัก</a>
        <div class="dropdown_2columns align_right">
            <div class="col_2">
                <ul class="simple">
                    <?php if ($make_money) { ?>
                        <li><a href="<?php echo site_url('user/account'); ?>">บัญชีผู้ใช้</a></li>
                    <?php } else { ?>

                        <li><a href="<?php echo site_url('user/account'); ?>">แก้ไขข้อมูลส่วนตัว</a></li>
                    <?php } ?>
                </ul>   
                <?php if ($rid == 3) { ?>
                    <h3>จัดการ content</h3>
                    <ul class="simple">
                        <li><a href="<?php echo site_url('resource/dycontent'); ?>">โจทย์, เนื้อหา</a></li>
                        <li><a href="<?php echo site_url('resource/video_manager'); ?>">วิดีโอ</a></li>
                        <li><a href="<?php echo site_url('resource/doc_manager'); ?>">แฟ้มเอกสาร</a></li>
                        <li><a href="<?php echo site_url('resource/image_manager'); ?>">รูปภาพ</a></li>
                        <li><a href="<?php echo site_url('resource/flash_media_manager'); ?>">บทเรียนแฟลช</a></li>

                    </ul>  

                    <h3>จัดการเรียนการสอน</h3>
                    <ul class="simple">
                        <li><a href="<?php echo site_url('resource/subject_manager'); ?>">กลุ่มสาระ, วิชา, บท</a></li>
                        <li><a href="<?php echo site_url('resource/sheet'); ?>">ใบงาน การบ้าน, การสอบ</a></li>
                        <li><a href="<?php echo site_url('study/course_manager'); ?>">หลักสูตรการสอน</a></li>
                        <li><a href="<?php echo site_url('study/course_manager/course_open'); ?>">คัดลอกหลักสูตรการสอนมาใช้</a></li>
                        <li><a href="<?php echo site_url('resource/taxonomy_manager'); ?>">ชุดวิดีโอการสอนบนหน้าเพจของตน</a></li>



                    </ul>  
                    <h3>รายงาน</h3>
                    <ul class="simple">
                        <li><a href="<?php echo site_url('report/teacher_report'); ?>">รายงานหลักสูตรการเรียน</a></li>
                        <li><a href="<?php echo site_url('report/play_report/show_all'); ?>">รายงานการเข้าชมวิดีโอ</a></li>
                        <?php if ($make_money) { ?>
                            <li><a href="<?php echo site_url('earnings/resource_earnings'); ?>">รายงานรายได้</a></li>
                        <?php } ?>
                    </ul>  
                    <h3>ค้นหา</h3>
                    <ul class="simple">
                        <li><a href="<?php echo site_url('search/teacher'); ?>">คันหาครู</a></li>
                        <li><a href="<?php echo site_url('search/teacher?qtype=school_name'); ?>">คันหาโรงเรียน</a></li>

                        <li><a href="<?php echo site_url('study/course_manager/course_open'); ?>">คันหาหลักสูตรที่เปิดสอน</a></li>
                    </ul> 

                    <?php
                } else if ($rid == 2) {
                    if ($make_money) {
                        ?>
                        <ul class="simple">
                            <li><a href="<?php echo site_url('page/truemoney_topup'); ?>">เติมเงิน</a></li>
                            <li><a href="<?php echo site_url('utopup/coupon/use_coupon'); ?>">ใช้คูปอง, รหัสหนังสือ</a></li>
                        </ul>  

                        <?php
                    } else {
                        ?>
                        <ul class="simple">
                            <li><a href="<?php echo site_url('study/course'); ?>">หลักสูตรที่เรียนอยู่</a></li>
                            <li><a href="<?php echo site_url('report/student_report'); ?>">รายงานผลการเรียน</a></li>
                            <li><a href="<?php echo site_url('study/course/course_open'); ?>">คันหาหลักสูตรที่เปิดสอน</a></li>
                            <li><a href="<?php echo site_url('search/teacher'); ?>">คันหาครู</a></li>
                            <li><a href="<?php echo site_url('search/teacher?qtype=school_name'); ?>">คันหาโรงเรียน</a></li>
                        </ul>  
                        <?php
                    }
                    ?>

                <?php } else if ($rid == 1) {
                    ?>
                    <ul class="simple">

                        <li><a href="<?php echo site_url('admin/users'); ?>">บริหารระบบ</a></li>
                        <li><a href="<?php echo site_url('utopup/manual_topup/informant_manager'); ?>">การเติมเงิน</a></li>

                        <li><a href="<?php echo site_url('resource/subject_manager/main_learning_area'); ?>">กลุ่มสาระ, วิชา, บท</a></li>
                        <li><a href="<?php echo site_url('report/play_report/show_all'); ?>">รายงานผลการเรียน</a></li>
                        <li><a href="<?php echo site_url('service/disk_quota_service'); ?>">พื้นที่อัพโหลดวิดีโอ</a></li>

                    </ul> 
                <?php } else if ($rid == 4) {
                    ?>
                    <ul class="simple">
                        <li><a href="<?php echo site_url('admin/users'); ?>">จัดการผู้ใช้</a></li>
                        <li><a href="<?php echo site_url('resource/subject_manager/main_learning_area'); ?>">กลุ่มสาระ, วิชา, บท</a></li>
                    </ul> 
               <?php } else if ($rid == 5) {
                    ?>
                    <ul class="simple">
                        <li><a href="<?php echo site_url('report/advance_report/filter/0'); ?>">รายงานสำหรับส่วนกลาง</a></li>
                    </ul> 
                <?php } ?>
            </div>
        </div>
    </li>
    <li class="menu"><a href="<?php echo site_url('user/logout'); ?>" class="drop">ลงชื่อออก</a></li>
<?php } else { ?>
    <?php if ($_SERVER['HTTP_HOST'] == 'www.educasy.com') { ?>
        <li class="menu"><a href="<?php echo site_url('user/register'); ?>" class="drop">สมัครสมาชิก</a>

        </li>
    <?php } else { ?>
        <li class="menu"><a href="#" class="drop">สมัครสมาชิก</a>
            <div class="dropdown_1column align_right">
                <div class="col_1">
                    <ul class="simple">
                        <li><a href="<?php echo site_url('user/register'); ?>">สำหรับนักเรียน</a></li>
                        <li><a href="<?php echo site_url('user/registerteacher'); ?>">สำหรับครู</a></li>

                    </ul> 
                </div>
            </div>
        </li>
    <?php } ?>
    <li class="menu">
        <a href="<?php echo site_url('user/login'); ?>" class="drop">ลงชื่อเข้าใช้</a>
    </li>

<?php } ?>
