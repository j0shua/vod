<h1 class="main-title"><?php echo $title; ?> <button class="btn-a" onclick="refresh_window();">เมนูเรียน</button> </h1>


<div  class="grid_12">
    <div id="myContent">

    </div>
</div>

<script type="text/javascript">
    $(function() {

        swfobject.embedSWF(swf_url, "myContent", "1180", "800", "9.0.0", expressInstall_url);

    });
    function refresh_window() {
        location.reload();
    }
</script>