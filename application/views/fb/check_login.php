<script>
    window.fbAsyncInit = function() {
        // Additional initialization code such as adding Event Listeners goes here
        FB.getLoginStatus(function(response) {
            console.log(response);
            if (response.status === 'connected') {
                alert("connected");
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token 
                // and signed request each expire
                var uid = response.authResponse.userID;
                var accessToken = response.authResponse.accessToken;
            } else if (response.status === 'not_authorized') {
                alert("not_authorized");
                FB.login(function(response) {
                    if (response.authResponse) {
                        console.log('Welcome!  Fetching your information.... ');
                        FB.api('/me', function(response) {
                            console.log('Good to see you, ' + response.name + '.');
                        });
                    } else {
                        console.log('User cancelled login or did not fully authorize.');
                    }
                });
            } else {
                alert("the user isn't logged in to Facebook");
                // the user isn't logged in to Facebook.
            }
        });
    };
</script>