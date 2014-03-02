<h1 class=""><?php echo $title; ?></h1>
<div class="grid_12">
    


    <div class="clearfix">
        <?php
        foreach ($grid_menu as $btn) {
            echo anchor($btn['url'], $btn['title'], 'class="btn-a" ' . $btn['extra']);
        }
        ?>
      
    </div>

    <table id="main-table" style="display: none"></table>
</div>



