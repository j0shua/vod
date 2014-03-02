var preview = null;
var obj_for_preview = "#explanation,.array_resource";
$(function(){
    $("#sortable" ).sortable();
    var preview_pass = false;
    $("markItUpEditor").change(function(){
        preview_pass = false;
    });
    $("#explanation").markItUp(xelatex_setting);
    var error_msg = [];
    $("#dialog").dialog({
        autoOpen:false,
        modal: true,
        width:960,
        height:500
    });
    $("#btnSubmit").click(function(){
        error_msg = ["== เตือน =="];
        var bvalid = true;
        if($("#title").val() == ''){
            bvalid = false;
            error_msg.push("จำเป้นต้องมีชื่อ");
        }
        if(explanation.val() == ''){
            bvalid = false;
            error_msg.push("จำเป็นต้องใส่ข้อมูลคำชี้แจงให้ครบ");
        }
            
        if(bvalid){
            if(!preview_pass){
                alert('โปรด preview ก่อน บันทึกโจทย์');
            }else{
                $("#content-form").submit();
            }
            
        }else{
            alert(error_msg.join("\n"));
        }
            
            
    });
    $("#btnpreview") .click(function(){
        preview();
    });
    
    preview = function(){
        data = $(obj_for_preview).serialize();
        data += '&render_type_id=1';
        
        $('body').showLoading();
        $.ajax({
            type: "POST",
            url: ajax_preview_url,
            data: data,
            dataType:"json",
            async:false
        }).done(function(json){
            if(json.status){
                preview_pass = true;
                $("#dialog").dialog({
                    title: "Preview" ,
                    width:960, 
                    closeText: "hide"
                });
                $("#dialog").dialog("open");
                $("#inner-dialog").html(json.render);
            }else{
                preview_pass = false;
                $("#dialog").dialog({
                    title: "Preview" ,
                    width:810
                });
                $("#dialog").dialog("open");
                $("#inner-dialog").html(json.render);
            }
                
                
        }).fail(function(jqXHR, textStatus){
            alert( "Request failed: " + textStatus +" "+jqXHR.status+" "+jqXHR.statusText);
            console.log(jqXHR);
        }).always(function(){
            $('body').hideLoading();
        });
    }
    $("#btnimage").click(function(){
        content_header.markItUp('insert',
        { 
            openWith:$("#title").val()
        });
    
    });
    $("body").delegate(".btn-delete-me", "click", function() {
        $(this).parent().remove();
    });
    $("#btnaddresource").click(function(){
        $("#dialog-add-resource").dialog({
            modal: true,
            width:400,
            height:400, 
            buttons: {
                "ตกลง": function() {
                    
                    var data = $(".array_resource").serialize();
                    data += "&resource_id=613,614,615,616";
                    $.ajax({
                        type: "POST",
                        url: ajax_li_resource_url,
                        data: data,
                        dataType:"json",
                        async:false
                    }).done(function(json){
                        if(json.status){
                            $("#sortable").append(json.li);
                            $("#sortable" ).sortable();
                            $("#dialog-add-resource").dialog("close");
                        }
            
                    }).fail(function(jqXHR, textStatus){
                        alert( "Request failed: " + textStatus +" "+jqXHR.status+" "+jqXHR.statusText);
                        console.log(jqXHR);
                    }).always(function(){
                        $('body').hideLoading();
                    });
                
                    
                },   
                "ยกเลิก": function() {
                    $(this).dialog("close");
                }
            }
        });
            
    });
        
});
function image_browser(o){
    $("#inner-dialog").html("<iframe title=\"โปรดรอ\" id=\"iframe-image-browser\" src=\""+image_browser_iframe_url+"/"+o.id+"\">โปรดรอ</iframe>");
    $("#dialog").dialog({
        title: "Image Browser" ,
        'width':810
    });
    $("#dialog").dialog("open");
}
function insert_image(id,image_file_path){
    var t = "\\includegraphics{"+image_file_path+"}";
    $("#"+id).markItUp('insert',
    { 
        openWith:t
    });
    $("#dialog").dialog("close");
}
    