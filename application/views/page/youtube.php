<h1><?php echo $title; ?> </h1>
<div class="grid_9">
    <div id='my-video'></div>
    <script type='text/javascript'>
        jwplayer('my-video').setup({
            file: youtube_url,
            width: '880',
            height: '540',
            autostart: true
        });
    </script>
</div>