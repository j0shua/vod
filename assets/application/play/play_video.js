var fplayer = null;
var netConnectionUrl = '';
var video_path = '';
var view_log_id = '';
var trigger_url = '';
var trigger_time = '';
var trigger_loop = null;
var request_init = null;
var request_trigger = null;
var request_trigger_time = '';
var request_trigger_timer = null;
var request_play_continue = null;
var request_close_and_play_next = null;
var now_resource_id = null;
var seek_complete = false;
var is_close = false;

var set_play_continue;
var is_fullscreen = false;
$(function() {
    now_resource_id = resource_id;
    init_play_video();
    $(window).unload(function() {
        var data = "view_log_id=" + view_log_id;
        $.ajax({
            type: "POST",
            url: ajax_close_play_url,
            data: data,
            dataType: "json",
            async: false
        });
    });
    $('#btn_continue_video').switchify().data('switch').bind('switch:slide', function(e, type) {
        console.log(type);
        set_play_continue(type);
    });
});
function init_play_video() {
    request_init = $.ajax({
        url: ajax_init_url,
        type: "POST",
        data: {
            resource_id: resource_id
        },
        dataType: "json"
    });
    request_init.done(function(msg) {
        video_path = msg.video_path;
        netConnectionUrl = msg.netConnectionUrl;
        trigger_url = msg.trigger_url;
        view_log_id = msg.view_log_id;
        request_trigger_time = msg.request_trigger_time;
        next_resource_id = msg.next_resource_id;
        on_last_second_url.continue_url = msg.continue_url;
        now_url = msg.now_url;
        start_play_video();
    });
    request_init.fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}
function start_play_video() {
    fplayer = $f("streams", "http://releases.flowplayer.org/swf/flowplayer-3.2.15.swf", {
        clip: {
            provider: 'cloudfront',
            url: video_path,
            onMetaData: function(clip) {
                //
                if (!seek_complete) {
                    seek_complete = true;
                    console.log(parseInt(seek_time));
                    fplayer.seek(parseInt(seek_time));
                }
                start_trigger();

                //
            },
            onLastSecond: function() {
                if (is_fullscreen) {

                    if (playcontinue && next_resource_id !== 0) {
                        fullscreen_play_next();
                    } else {
                        close_play_video();
                    }
                } else {
                    if (playcontinue) {
                        window.location.href = on_last_second_url.continue_url;
                    } else {
                        window.location.href = on_last_second_url.not_continue_url;
                    }
                }


//site_url("play/play_resource/confirm_playagain/" + resource_id);
            },
            scaling: 'fit'
        },
        plugins: {
            cloudfront: {
                url: "flowplayer.rtmp-3.2.11.swf",
                netConnectionUrl: netConnectionUrl
            }
        },
        onFullscreen: function() {
            is_fullscreen = true;
        }, onFullscreenExit: function() {
//            var tmp = (fplayer.getTime()) + parseFloat(9);
//            console.log(tmp);
            // fplayer.seek(tmp);
            is_fullscreen = false;
            if (now_resource_id !== resource_id) {
                window.location.href = now_url + "?t=" + parseInt(fplayer.getTime());

//                if (playcontinue) {
//                    window.location.href = on_last_second_url.continue_url;
//                    
//                } else {
//                    window.location.href = on_last_second_url.not_continue_url;
//                }
            }
            if (next_resource_id === 0 && is_close) {
                window.location.href = on_last_second_url.not_continue_url;
            }
        }
    });
}
function trigger_play_video() {
    request_trigger = $.ajax({
        url: trigger_url,
        type: "POST",
        data: {
            resource_id: resource_id,
            view_log_id: view_log_id
        },
        dataType: "json"
    });
    request_trigger.done(function(msg) {
        if (msg.can_play) {
            start_trigger();
        } else {
            stop_trigger();
            fplayer.close();
            window.location.reload();
        }
    });
    request_trigger.fail(function(jqXHR, textStatus) {
        alert("connection fail");
        fplayer.close();
        window.location.reload();
    });
}
function start_trigger() {
    stop_trigger();
    request_trigger_timer = setTimeout("trigger_play_video()", request_trigger_time);
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
    fplayer.close();
    is_close = true;
//ajax close video
}

set_play_continue = function(set_continue) {
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
        fplayer.close();
        window.location.reload();
    });
};
function fullscreen_play_next() {
    request_close_and_play_next = $.ajax({
        url: ajax_close_and_play_next_url,
        type: "POST",
        data: {
            resource_id: next_resource_id, // next resourcr_id
            view_log_id: view_log_id
        },
        dataType: "json"
    });
    request_close_and_play_next.done(function(msg) {
        video_path = msg.video_path;
        netConnectionUrl = msg.netConnectionUrl;
        trigger_url = msg.trigger_url;
        view_log_id = msg.view_log_id;
        request_trigger_time = msg.request_trigger_time;
        next_resource_id = msg.next_resource_id;
        now_resource_id = msg.now_resource_id;
        on_last_second_url.continue_url = msg.continue_url;
        now_url = msg.now_url;
        fplayer.play(video_path);
        start_trigger();
    });
    request_close_and_play_next.fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}
