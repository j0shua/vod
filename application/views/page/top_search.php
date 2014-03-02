<div class="grid_4 clearfix" id="top-search">
    <?php if ($make_money) { ?>
        <form id="form-top-search" action="<?php echo site_url('play/bookplayer/do_search'); ?>">
            <input id="top-search_text" type="text" name="search_text" maxlength="20" placeholder="กรอกรหัสจากหนังสือเพื่อเข้าชมวิดีโอ" autocomplete="off">
        <?php } else { ?>
            <form id="form-top-search" action="<?php echo site_url('play/bookplayer/do_search'); ?>">
                <input id="top-search_text" type="text" name="search_text" maxlength="20" placeholder="พิมพ์ชื่อครูเพื่อค้นหา" autocomplete="off">       
            <?php } ?>
            <input type="submit" value="" id="btn-top-search-submit"/>
        </form>   
</div>
<style>

</style>
<script>
    $("#form-top-search").submit(function() {
        $("#top-search_text").val($.trim($("#top-search_text").val()));
        if ($("#top-search_text").val() == '') {
            return false;
        }
        return true;
    });
</script>
