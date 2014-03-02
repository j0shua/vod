<div class="grid_12">
    sdsd
<fb:login-button show-faces="true" width="200" max-rows="1"></fb:login-button>


<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    
    js.src = "//connect.facebook.net/th_TH/all.js#xfbml=1&appId=<?php echo $facebook_appId;  ?>";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</div>