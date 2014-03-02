<?php

/* * ========================================================================
 * VDO Variable
 * เกี่ยวกับระบบการ upload vdo convert vdo
 * ========================================================================
 */

/*
  |--------------------------------------------------------------------------
  | ค่ากำหนดต่างๆ ของ ffmpeg
  |--------------------------------------------------------------------------
  |
 */
$config['ffmpeg_option'] = array(
    // output config ***********************
    'output_ext' => 'flv',
    'VideoCodec' => 'flv',
    'AudioCodec' => 'libmp3lame',
//   'output_ext' => 'mp4',
//   'VideoCodec' => 'libx264',
//   'AudioCodec' => 'aac',
    // output config ***********************
    'VideoBitRate' => 530000,
    'FrameRate' => 25,
    'AudioBitRate' => 64500,
    'AudioSampleRate' => 11025,
    'FrameHeight' => 240,
    'FrameWidth' => 320,
    'optional' => '-strict experimental'
);
$config['ffmpeg_codec_map'] = array(
    // encode option to set encode => encode read form phpffmpeg
    'libx264' => 'h264',
    'flv' => 'flv',
    'libmp3lame' => 'mp3',
    'aac' => 'aac'
);