<div class="grid_12">
    <input type="button" value="preview" id="btnpreview" class="btn-submit" >
</div>
<script>
    $(function(){
        $("#btnpreview").click(function(){
            $('body').showLoading();
            setTimeout( "$('body').hideLoading()", 5000 );
        });
    });
</script>