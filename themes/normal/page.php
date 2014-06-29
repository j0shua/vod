<!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html xmlns:fb="http://ogp.me/ns/fb#"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title><?php echo $title; ?></title>
        <meta name="description" content="<?php echo $meta_description; ?>">
        <meta name="author" content="lojorider">
        <meta property="og:image" content="<?php echo $og_image; ?>"/>
        <!-- Mobile viewport optimized: j.mp/bplateviewport -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- Place favicon.ico in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="<?php echo base_url('themes/normal/favicon.png'); ?>">

        <!-- CSS: implied media="all" -->    
        <link rel="stylesheet" href="<?php echo base_url('themes/normal/css/style.css?v=2'); ?>">

        <link rel="stylesheet" href="<?php echo $template_url . 'css/960_12_col.css'; ?>" />

        <?php echo $link; ?>
        <!-- Help fixing for IE browsers -->
        <!--[if lt IE 9]>
        <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
        <![endif]-->
        <!-- All JavaScript at the bottom, except for Modernizr which enables HTML5 elements & feature detects -->
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
    </head>
    <body> 
        <div id="main-wrapper" class="clearfix">
            <?php echo $content; ?>
        </div>
       
    </body>
</html>