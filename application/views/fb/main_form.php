
<fb:registration redirect-uri="http://www.educasy.com/fb"
                 fields='<?php echo $custom_fields_json; ?>' ;
                 onvalidate="validate_async"></fb:registration> 

<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script> 
    function validate_async(form, cb) {
        $.getJSON(site_url('facebook/register/ajax_check_username/'+form.username), 
        function(response) {
            if (response.can_use) {
                cb();
            }
            cb({username: 'Username ถูกใช้ไปแล้ว'});
        });
    }
</script>


<script src="http://connect.facebook.net/th_TH/all.js"></script>
<script type="text/javascript" >
    FB.init({
        appId  : '<?php echo $facebook_appId; ?>',
        status : true,
        cookie : true,
        xfbml  : true
    });
</script>
