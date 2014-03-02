<div class="grid_12">
    <h1 class="main-title">ทดสอบ markItUp</h1>
    <textarea id="markItUp" cols="80" rows="20"></textarea>
</div>

<script type="text/javascript">
    $(function() {
        // Add markItUp! to your textarea in one line
        // $('textarea').markItUp( { Settings }, { OptionalExtraSettings } );
        $('#markItUp').markItUp(mySettings);



        // You can add content from anywhere in your page
        // $.markItUp( { Settings } );	
        $('.add').click(function() {
            $('#markItUp').markItUp('insert',
            { 	openWith:'<opening tag>',
                closeWith:'<\/closing tag>',
                placeHolder:"New content"
            }
        );
            return false;
        });
	
        // And you can add/remove markItUp! whenever you want
        // $(textarea).markItUpRemove();
        $('.toggle').click(function() {
            if ($("#markItUp.markItUpEditor").length === 1) {
                $("#markItUp").markItUp('remove');
                $("span", this).text("get markItUp! back");
            } else {
                $('#markItUp').markItUp(mySettings);
                $("span", this).text("remove markItUp!");
            }
            return false;
        });
    });
</script>