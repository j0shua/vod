<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of phpavconv
 *
 * @author lojorider
 */
class phpavconv {

    var $ci, $ffmpeg_option, $ffmpeg_codec_map, $full_encode_log_dir;
    var $debug = FALSE;
    var $VideoCodec, $VideoBitRate, $FrameRate, $AudioCodec, $AudioBitRate, $AudioSampleRate, $FrameHeight, $FrameWidth;

    public function __construct() {
        $this->ci = & get_instance();
        $this->ci->config->load('phpavconv');
        $this->ffmpeg_option = $this->ci->config->item('ffmpeg_option');
        $this->ffmpeg_codec_map = $this->ci->config->item('ffmpeg_codec_map');
    }

    private function init_video_info($input_file) {
        $movie = new ffmpeg_movie($input_file);
        $this->VideoCodec = $movie->getVideoCodec();
        $this->VideoBitRate = $movie->getVideoBitRate();
        if ($this->VideoBitRate === FALSE) {
            $this->VideoBitRate = 4000000;
        }
        $this->FrameRate = $movie->getFrameRate();
        $this->AudioCodec = $movie->getAudioCodec();
        $this->AudioBitRate = $movie->getAudioBitRate();
        $this->FrameHeight = $movie->getFrameHeight();
        $this->FrameWidth = $movie->getFrameWidth();
        $this->AudioSampleRate = $movie->getAudioSampleRate();
    }

    function encode($input_full_file_path, $output_full_file_path, $log_full_file_path = FALSE, $return_command = FALSE) {
        $this->init_video_info($input_full_file_path);

        if (file_exists($input_full_file_path)) {
            if ($this->debug) {
                rename($input_full_file_path, $output_full_file_path);
            } else {

                $options = $this->make_encode_options($input_full_file_path);
                if ($log_full_file_path) {
                    $command = "avconv  -i $input_full_file_path $options $output_full_file_path 1>$log_full_file_path 2>&1 &";
                } else {
                    $command = "avconv  -i $input_full_file_path $options $output_full_file_path 1>/dev/null 2>&1 &";
                }

                if ($return_command) {
                    return $command;
                } else {
                   // exit($command);
                    exec($command);
                }
            }
            return TRUE;
        }
        return FALSE;
    }

    function get_file_output_extension() {
        return $this->ffmpeg_option['output_ext'];
    }

    private function make_encode_options() {
        $option = array(
            //  '-s' => '',
            '-vcodec' => '',
            '-r' => '',
            '-b' => '',
            '-ab' => '',
            '-ar' => '',
                //  '-aspect' => ''
        );
        // check Frame Size
//        if ($this->FrameHeight > $this->ffmpeg_option['FrameHeight']) {
//            // cal FrameWidth
//            //$option['-aspect'] = $this->FrameWidth() / $this->getFrameHeight();
//            //$option['-s'] = $this->ffmpeg_option['FrameWidth'] . 'x' . $this->ffmpeg_option['FrameHeight'];
//        }
        // check video code
        if ($this->VideoCodec != $this->ffmpeg_codec_map[$this->ffmpeg_option['VideoCodec']]) {

            $option['-vcodec'] = $this->ffmpeg_option['VideoCodec'];
            if ($this->VideoBitRate < $this->ffmpeg_option['VideoBitRate']) {
                $option['-b'] = $this->VideoBitRate;
            } else {
                $option['-b'] = $this->ffmpeg_option['VideoBitRate'];
            }
        } else if ($this->VideoBitRate > $this->ffmpeg_option['VideoBitRate']) {

            $option['-vcodec'] = $this->ffmpeg_option['VideoCodec'];
            $option['-b'] = $this->ffmpeg_option['VideoBitRate'];
        } else {
            $option['-vcodec'] = 'copy';
            unset($option['-b']);
        }

        if ($this->FrameRate > $this->ffmpeg_option['FrameRate']) {
            $option['-r'] = $this->ffmpeg_option['FrameRate'];
        }
        if ($this->AudioCodec != $this->ffmpeg_codec_map[$this->ffmpeg_option['AudioCodec']]) {
            $option['-acodec'] = $this->ffmpeg_option['AudioCodec'];
            $option['-ab'] = $this->ffmpeg_option['AudioBitRate'];
            $option['-ar'] = $this->ffmpeg_option['AudioSampleRate'];
        } else if ($this->AudioBitRate > $this->ffmpeg_option['AudioBitRate']) {
            $option['-acodec'] = $this->ffmpeg_option['AudioCodec'];
            $option['-ab'] = $this->ffmpeg_option['AudioBitRate'];
            $option['-ar'] = $this->ffmpeg_option['AudioSampleRate'];
        } else if ($this->AudioSampleRate > $this->ffmpeg_option['AudioSampleRate']) {
            $option['-acodec'] = $this->ffmpeg_option['AudioCodec'];
            $option['-ab'] = $this->ffmpeg_option['AudioBitRate'];
            $option['-ar'] = $this->ffmpeg_option['AudioSampleRate'];
        } else {
            $option['-acodec'] = 'copy';
            unset($option['-ab']);
        }
        $str_option = array();
        foreach ($option as $k => $v) {
            if ($v != '') {
                $str_option[] = $k . ' ' . $v;
            }
        }
        if (isset($this->ffmpeg_option['optional'])) {
            $str_option[] = $this->ffmpeg_option['optional'];
        }
        //exit(implode(' ', $str_option));
        return implode(' ', $str_option);
    }

}

