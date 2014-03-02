var video_player = null;
var init_var = null;
var is_close = false;
var request_close_and_play_next = null;
var request_trigger_timer = null;
var seek_complete = false;
var debug = true;

var set_play_continue = function(set_continue) {
    if (set_continue === 'on') {
        set_continue = "1";
        playcontinue = true;
    } else {
        set_continue = "0";
        playcontinue = false;
    }

    request_play_continue = $.ajax({
        url: ajax_set_play_continue_url,
        type: "POST",
        data: 'play_continue=' + set_continue,
        dataType: "json"
    });
    request_play_continue.done(function(json) {

    });
    request_play_continue.fail(function(jqXHR, textStatus) {
        alert("connection fail");
        video_player.close();
        window.location.reload();
    });
};

$(function() {
    init_play_video();

    $('#btn_continue_video').switchify().data('switch').bind('switch:slide', function(e, type) {
        console_log(type);
        set_play_continue(type);
    });
});
//ดึงข้อมูลการเล่นวิดีโอ
function init_play_video() {
    request_init = $.ajax({
        url: ajax_init_url,
        type: "POST",
        data: {
            resource_id: resource_id,
            referer_url: referer_url

        },
        dataType: "json"
    });
    request_init.done(function(json) {
        init_var = json;
        console_log(init_var);
        if (is_rtmp) {
            start_play_video_rtmp();//เริมเล่น rtmp
        } else {
            start_play_video(); //เริมเล่น
        }

    });
    request_init.fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);

    });
    $(window).unload(function() {
        var data = "view_log_id=" + init_var.view_log_id;
        $.ajax({
            type: "POST",
            url: ajax_close_play_url,
            data: data,
            dataType: "json",
            async: false
        });
    });
}
function start_play_video() {

    video_player = $f("my-video", flowplayer_url, {
        clip: {
            url: init_var.jwplayer_file,
            onMetaData: function(clip) {
                if (!this.isFullscreen()) {
                    if (seek_time > 0 && !seek_complete) {
                        video_player.seek(seek_time);
                        seek_complete = true;
                    }
                }
                start_trigger();
            },
            onFinish: function() {
                if (this.isFullscreen()) {
                    if (playcontinue && init_var.next_resource_id !== 0) {
                        fullscreen_play_next();
                    } else {
                        close_play_video();
                    }
                } else {
                    if (playcontinue) {
                        window.location.href = init_var.continue_url;
                    } else {
                        window.location.href = init_var.not_continue_url;
                    }
                }

            },
            scaling: 'fit'
        }, onFullscreenExit: function() {

            if ((parseInt(init_var.now_resource_id) !== parseInt(resource_id))) {

                window.location.href = init_var.now_url + "?t=" + parseInt(this.getTime());


            }
        }
    });
}
function start_play_video_rtmp() {

    //video_player = $f("my-video", "http://releases.flowplayer.org/swf/flowplayer-3.2.15.swf", {
    video_player = $f("my-video", flowplayer_url, {
        clip: {
            provider: 'cloudfront',
            url: init_var.video_path,
            onMetaData: function(clip) {
                if (!this.isFullscreen()) {
                    if (seek_time > 0 && !seek_complete) {
                        video_player.seek(seek_time);
                        seek_complete = true;
                    }
                }
                start_trigger();
            },
            onFinish: function() {
                if (this.isFullscreen()) {
                    if (playcontinue && init_var.next_resource_id !== 0) {
                        fullscreen_play_next();
                    } else {
                        close_play_video();
                    }
                } else {
                    if (playcontinue) {
                        window.location.href = init_var.continue_url;
                    } else {
                        window.location.href = init_var.not_continue_url;
                    }
                }

            },
            scaling: 'fit'
        }, onFullscreenExit: function() {

            if ((parseInt(init_var.now_resource_id) !== parseInt(resource_id))) {

                window.location.href = init_var.now_url + "?t=" + parseInt(this.getTime());


            }
        },
        plugins: {
            cloudfront: {
                url: flowplayer_rtmp_file,
                netConnectionUrl: init_var.netConnectionUrl
            }
        }
    });
}
function trigger_play_video() {
    request_trigger = $.ajax({
        url: init_var.trigger_url,
        type: "POST",
        data: {
            view_log_id: init_var.view_log_id
        },
        dataType: "json"
    });
    request_trigger.done(function(msg) {
        if (msg.can_play) {
            start_trigger();
        } else {
            stop_trigger();

            video_player.close();
            window.location.reload();
        }
    });
    request_trigger.fail(function(jqXHR, textStatus) {
        alert("connection fail");
        video_player.close();
        window.location.reload();
    });
}
function start_trigger() {

    stop_trigger();
    request_trigger_timer = setTimeout("trigger_play_video()", init_var.request_trigger_time);
    console.log(request_trigger_timer);

}
function stop_trigger() {
    if (request_trigger_timer !== null) {
        clearTimeout(request_trigger_timer);
    }
    request_trigger_timer = null;
}
function close_play_video() {
    stop_trigger();
    video_player.close();
    is_close = true;
//ajax close video
}


function fullscreen_play_next() {
    request_close_and_play_next = $.ajax({
        url: ajax_close_and_play_next_url,
        type: "POST",
        data: {
            resource_id: init_var.next_resource_id, // next resourcr_id
            view_log_id: init_var.view_log_id
        },
        dataType: "json"
    });
    request_close_and_play_next.done(function(json) {
        init_var = json;
        //video_player.load([{file: init_var.jwplayer_file}]);
        if (is_rtmp) {
            video_player.play({url: init_var.video_path, autoplay: true})
        } else {
            video_player.play({url: init_var.jwplayer_file, autoplay: true})
        }


        console.log(init_var);
        start_trigger();
    });
    request_close_and_play_next.fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}
function console_log(msg) {
    if (debug) {
        console.log(msg);
    }
}
