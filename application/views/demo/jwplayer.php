<div id='my-video'></div>
<script type='text/javascript'>
    jwplayer('my-video').setup({
        file: 'http://www.youtube.com/watch?v=vmXn7ub3oq8',
        width: '640',
        height: '360',
        autostart: true
    });
    jwplayer('my-video').onResize(function() {

        console.log(this.getFullscreen());
        if (!this.getFullscreen()) {
            alert("sadsada");
        }
    });
    jwplayer('my-video').onComplete(function() {
        console.log('end');
        jwplayer('my-video').load([{file: "http://www.youtube.com/watch?v=5H4Lg-rl58U&list=RD02vmXn7ub3oq8"}]);
        //jwplayer('my-video').play(state);
    });
//    jwplayer('my-video').onPlaylist(function() {
//        jwplayer('my-video').play();
//    });
</script>