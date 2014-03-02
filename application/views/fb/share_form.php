<h1><?php echo $title; ?></h1>
<div class="grid_12">
    <div id="fb-share">
        <h2>แชร์เว็บนี้</h2>

        <form class="normal-form" method="post" action="<?php echo $form_action; ?>" id="main_form" autocomplete="off">

            <p>


                <textarea id="message" name="message" maxlength="255" placeholder="เขียนอะไรบางอย่าง..."></textarea>
            </p>


            <input type="submit" class="btn-submit" id="form_submit" name="submit" value="แชร์ทันที">
            <p class="hide important" id="response"></p>

        </form>
    </div>

</div>



<script>
    $(function() {
        $("#main_form").submit(function() {
            
            $("#message").val($.trim($("#message").val()));
            
            if ($("#message").val() === "") {

                alert("เขียนอะไรหน่อยดีไหม");
                return false;
            }
            return true;
        });
    });

    window.fbAsyncInit = function() {

        FB.Event.subscribe('edge.create', function(href, widget) {
            $.ajax({
                type: "POST",
                url: ajax_fb_like_url,
                data: data,
                dataType: "json",
                async: false
            }).done(function(json) {
                if (json.status) {
                    preview_pass = true;
                    $("#dialog").dialog({
                        title: "Preview",
                        width: 960,
                        closeText: "hide"
                    });
                    $("#dialog").dialog("open");
                    $("#inner-dialog").html(json.render);
                } else {
                    preview_pass = false;
                    $("#dialog").dialog({
                        title: "Preview",
                        width: 810
                    });
                    $("#dialog").dialog("open");
                    $("#inner-dialog").html(json.render);
                }


            }).fail(function(jqXHR, textStatus) {
                alert("Request failed: " + textStatus + " " + jqXHR.status + " " + jqXHR.statusText);
                console.log(jqXHR);
            }).always(function() {
                $('body').hideLoading();
            });
        }
        );
        FB.Event.subscribe('edge.remove', function(href, widget) {
            // alert('You just unliked ' + href);
        });
    };
</script>
<style>
    #fb-share{
        border: 1px solid #3B5998;   
        width: 550px;
    }
    #fb-share h2{
        background-color: #6D84B4;
        color: #FFFFFF;
        display: block;
        font-size: 18px;
        line-height: 25px;
        margin-bottom: 15px;
        text-indent: 10px;
        width: 100%;

    }
    #fb-share textarea{
        margin-left: 15px;
        width: 510px;
    }
    #form_submit{
        margin-left: 15px;
        background-color: #5F78AB;
        border: solid 1px #3B5998;
    }
</style>
