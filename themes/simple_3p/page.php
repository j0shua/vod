<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <meta name="copyright" content="Licensed under GPL and MIT." />
        <meta name="description" content="<?php echo $meta_description; ?>">
        <meta name="author" content="lojorider">
        <meta property="og:image" content="<?php echo $og_image; ?>"/>
        <title><?php echo $title; ?></title>
        <link rel="icon" type="image/png" href="<?php echo $template_url . 'images/favicon.png'; ?>">
        <link rel="stylesheet" href="<?php echo base_url('/files/fonts/stylesheet.css'); ?>" />
        <link rel="stylesheet" href="<?php echo $template_url . 'css/master.css'; ?>" />
        <noscript>
        <link rel="stylesheet" href="<?php echo $template_url . 'css/mobile.min.css'; ?>" />
        </noscript>
        <script>
            // Edit to suit your needs.
            var ADAPT_CONFIG = {
                // Where is your CSS?
                path: '<?php echo $template_url . 'css'; ?>/',
                // false = Only run once, when page first loads.
                // true = Change on window resize and page tilt.
                dynamic: true,
                // First range entry is the minimum.
                // Last range entry is the maximum.
                // Separate ranges by "to" keyword.
                range: [
//                    '0px    to 760px  = mobile.min.css',
//                    '760px  to 980px  = 720.min.css',
//                    '980px  to 1280px = 960.min.css',
//                    '1280px to 2540px = 1200.min.css',.
                    '0px to 2540px = 1200.min.css'
//                    '1600px to 1940px = 1560.min.css',
//                    '1940px to 2540px = 1920.min.css',
//                    '2540px           = 2520.min.css'
                ]
            };
        </script>
        <script src="<?php echo base_url('assets/adapt/adapt.min.js'); ?>"></script>
        <script>
            function site_url(uri) {
                uri = (uri == undefined) ? '' : uri;
                return '<?php echo site_url() ?>' + uri;
            }
            function base_url(uri) {
                uri = (uri == undefined) ? '' : uri;
                return '<?php echo base_url() ?>' + uri;
            }
        </script>
        <?php echo $script; ?>
        <?php echo $link; ?>
    </head>
    <body>
        <?php if ($make_money) { ?>
            <div id="fb-root"></div>
            <!--        เริ่มต้น java script สำหรับ facebook-->
            <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id))
                        return;
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//connect.facebook.net/th_TH/all.js#xfbml=1&appId=<?php echo $facebook_appId; ?>";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
        <?php } ?>
        <div id="nav-wraper" class="container_full clearfix">
            <div class="container_12" id="head_wrapper">

                <div class="top-bar-logo alpha omega">
                    <a href="<?php echo site_url(); ?>"><img src="<?php echo base_url('themes/simple_3p/images/logo3p.png'); ?>" title="educasy" alt="educasy"></a>
                </div>
                <div class="grid_6 top-bar-title alpha omega">
                  
                </div>

                <?php //echo $topbar; ?>
                <!--  top menu -->
                <style>
                    #top-display-name{
                        float: left;
                        display: block;
                        padding-top: 10px;
                        padding-right: 10px;
                        color: #ffffff;
                    }
                    #top-display-name img{

                        display: block;
                        float: left;
                    }
                    #top-display-name span{
                        line-height: 32px;
                        margin-left: 10px;
                        display: block;
                        float: left;
                        font-size: 16px;
                    }
                </style>

                <div class="to-right top-bar-nav"> 
                    <?php echo $top_display_name; ?>

                    <ul class="top-menu to-right">
                        <?php
                        if (FALSE) {
                            foreach ($top_menu as $menu) {
                                if (!isset($menu['extra'])) {
                                    $menu['extra'] = '';
                                }
                                echo '<li><a class="drop" href="' . $menu['href'] . '" title="' . $menu['title'] . '" ' . $menu['extra'] . '>' . $menu['text'] . '</a>';
                                if (isset($menu['sub_menu'])) {
                                    if ($menu['sub_menu'] != '') {


                                        echo '<div class="dropdown_2columns align_right">
  
                                  <div class="col_2">
  
                                      <ul class="simple">';
                                        foreach ($menu['sub_menu'] as $sub_menu) {
                                            if (!isset($sub_menu['extra'])) {
                                                $sub_menu['extra'] = '';
                                            }
                                            $sub_menu['title'] = (isset($sub_menu['title'])) ? $sub_menu['title'] : '';
                                            echo '<li><a href="' . $sub_menu['href'] . '" title="' . $sub_menu['title'] . '" ' . $sub_menu['extra'] . '>' . $sub_menu['text'] . '</a>';
                                        }
                                        echo '         </ul>   
                                  </div>
                              </div>';
                                    }
                                }
                                echo '</li>';
                            }
                        } else {
                            echo $user_menu;
                        }
                        ?>

                    </ul>
                </div>
                <!-- top menu -->
            </div>
        </div>
        <div class="container_12">
            <div id="main-wrapper" class="clearfix">
                <?php echo $content; ?>
            </div>
            <hr class="grid_12" />
        </div>
        <div id="footer-wrapper" class="clearfix">
            <div class="container_12">
                <div class="grid_12 align_center">
                    <p>
                        <?php echo $footer; ?>
                    </p>
                </div>
            </div>
        </div>
        <!-- footer -->
    </body>
</html>