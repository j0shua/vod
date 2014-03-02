var swfu;

window.onload = function () {
    swfu = new SWFUpload({
        // Backend settings
        upload_url: site_url("resource/video_upload/swfupload"),
        //upload_url: base_url("upload.php"),
        file_post_name: "resume_file",

        // Flash file settings
        file_size_limit : "2 GB",
        file_types : file_types,			// or you could use something like: "*.doc;*.wpd;*.pdf",
        file_types_description : "All Files",
        file_upload_limit : "0",
        file_queue_limit : "1",

        // Event handler settings
        swfupload_loaded_handler : swfUploadLoaded,
				
        file_dialog_start_handler: fileDialogStart,
        file_queued_handler : fileQueued,
        file_queue_error_handler : fileQueueError,
        file_dialog_complete_handler : fileDialogComplete,
				
        //upload_start_handler : uploadStart,	// I could do some client/JavaScript validation here, but I don't need to.
        upload_progress_handler : uploadProgress,
        upload_error_handler : uploadError,
        upload_success_handler : uploadSuccess,
        upload_complete_handler : uploadComplete,

        // Button Settings
        button_image_url : base_url("assets/swfupload/images/NTButtonNoText_62x34.png"),
        button_placeholder_id : "spanButtonPlaceholder",
        button_action: -150,
        button_text_top_padding:  3,
        button_text:              '<span class="swf-btn-upload"></span>',
        button_text_style:        ".swf-btn-upload { font-size: 12; }",
        button_text_left_padding: 6,
        button_width: 62,
        button_height: 34,
				
        // Flash Settings
        flash_url : base_url("assets/swfupload/swfupload.swf"),

        custom_settings : {
            progress_target : "fsUploadProgress",
            upload_successful : false
        },
				
        // Debug settings
        debug: true
    });

};
