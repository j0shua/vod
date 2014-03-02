var fplayer = null;
var ajax_init_url = site_url('play/play_resource/ajax_init_play_video_money_bonus');
var ajax_close_play_url =site_url('play/play_resource/ajax_close_play_video');
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
jQuery(function(){
    init_play_video();
    $(window).unload(function(){
        var data ="view_log_id="+view_log_id;
        $.ajax({
            type: "POST",
            url: ajax_close_play_url,
            data: data,
            dataType:"json",
            async:false
        });
    });
});
function init_play_video(){
    request_init = $.ajax({
        url: ajax_init_url,        
        type: "POST",        
        data: {
            resource_id : resource_id
        },
        dataType: "json"
    });
    request_init.done(function(msg) {
        video_path = msg.video_path;
        netConnectionUrl = msg.netConnectionUrl;
        trigger_url = msg.trigger_url;
        view_log_id = msg.view_log_id;
        request_trigger_time = msg.request_trigger_time;
        
        start_play_video();
    });
    request_init.fail(function(jqXHR, textStatus) {
        alert( "Request failed: " + textStatus );
    });
}
function start_play_video(){
    fplayer = $f("streams", "http://releases.flowplayer.org/swf/flowplayer-3.2.15.swf", {
        clip: {
            provider: 'cloudfront',
            url: video_path,
            onMetaData: function(clip) {
                start_trigger();
            },
            onLastSecond:function(){
                window.location.href= site_url("play/play_resource/confirm_playagain/"+resource_id);
            },
            scaling:'fit'
        },
        
        plugins: {
            cloudfront: {
                url: "flowplayer.rtmp-3.2.11.swf",
                netConnectionUrl: netConnectionUrl
            }
        }
    });
	
  
}
function trigger_play_video(){
    request_trigger = $.ajax({
        url: trigger_url,
        type: "POST",
        data: {
            resource_id : resource_id,
            view_log_id : view_log_id
        },
        dataType: "json"
    });
    request_trigger.done(function(msg) {
        if(msg.can_play){
            start_trigger();
        }else{
            stop_trigger();
            fplayer.close();
            alert("เครดิตเวลาของคุณหมดแล้ว ระบบจะปรับไปใช้แบบใช้เงิน");
            window.location.reload();
        }
    });
    request_trigger.fail(function(jqXHR, textStatus) {
        alert("connection fail");
        fplayer.close();
        window.location.reload();
    });
}
function start_trigger(){
    stop_trigger();
    request_trigger_timer = setTimeout("trigger_play_video()", request_trigger_time);
    console.log(request_trigger_timer);
    
    
}
function stop_trigger(){
    if(request_trigger_timer != null){
        clearTimeout(request_trigger_timer);    
    }
    request_trigger_timer = null;
}
function close_play_video(){
    stop_trigger();
    fplayer.close();
//ajax close video
}

