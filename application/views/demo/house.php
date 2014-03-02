<div class="grid_12 line_space_10"></div>
<div class="grid_3">
    <div class="border">
        <h2>รักเรียน ดีเลิศ</h2>
        <p>
            xxxxxxxxxxxxxxxxxxxxxxxx xxxxxxxxxxxx xxxxxxxxxxxxxx
        </p>

    </div>
    <div class="border">
        <h2>ADS</h2>
        <ul>
            <li><a href="#">สินค้า</a></li>
            <li><a href="#">สินค้า</a></li>
            <li><a href="#">สินค้า</a></li>
            <li><a href="#">สินค้า</a></li>
        </ul>

    </div>
</div>
<div class="grid_6">

    <h1 class="border">วิชาฟิสิกส์</h1>

    <div class="border">
        <ul>
            <li>
                <h3>บทที่ 1 บทนำ</h3>
                <ul>
                    <li><a href="#">1 xxxxxxxxxxxxxxxx</a></li>
                    <li><a href="#">2 xxxxxxxxxxxxxxxx</a></li>

                </ul>
            </li>
            <li>
                <h3>บทที่ 2 การเคลื่อนที่</h3>
                <ul>
                    <li><a href="#">1 xxxxxxxxxxxxxxxx</a></li>
                    <li><a href="#">2 xxxxxxxxxxxxxxxx</a></li>

                </ul>
            </li>
        </ul>
    </div>
</div>
<div class="grid_3">
    <div class="border">
        <h2>รายชื่อวิชา</h2>
        <ul>
            <?php
            foreach ($taxonomy_parent as $v) {
                
            }
            ?>
            <li><a href="#">วิชาฟิสิกส์</a></li>
            <li><a href="#">วิชาเคมี</a></li>
        </ul>
    </div>
    <div class="border">
        <h2>ADS</h2>
        <ul>
            <li><a href="#">สินค้า</a></li>
            <li><a href="#">สินค้า</a></li>
            <li><a href="#">สินค้า</a></li>
            <li><a href="#">สินค้า</a></li>
        </ul>
    </div>
</div>


<style>
    .border{
        box-sizing:border-box;
        -moz-box-sizing:border-box;
        -webkit-box-sizing:border-box;
        border: 1px solid #CCCCCC;
        padding: 5px;
        padding-bottom: 20px;
        margin-bottom: 15px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;

    }
    h1.border{
        margin-bottom: 4px;
        padding-bottom: 5px;
    }
    .line_space_10{
        margin-bottom: 10px;
    }

</style>