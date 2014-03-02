<?php

/**
 * CodeIgniter BBCode Helpers
 *
 * @package  CodeIgniter
 * @subpackage Helpers
 * @category Helpers
 * @author  Philip Sturgeon
 * @changes  MpaK http://mrak7.com
 * @link  http://codeigniter.com/wiki/BBCode_Helper/
 */
// ------------------------------------------------------------------------

/**
 * parse_bbcode
 *
 * Converts BBCode style tags into basic HTML
 *
 * @access public
 * @param string unparsed string
 * @param int max image width
 * @return string
 */
function parse_code_tag($str) {
    $str = htmlentities($str, ENT_QUOTES);
    $find = array('[');
    $replace = array('&lbrack;', '&rbrack;');
    return '<pre class="code">' . str_replace($find, $replace, $str) . '</pre>';
}

function parse_bbcode($str = '', $max_images = 0) {
// Max image size eh? Better shrink that pic!
    if ($max_images > 0):
        $str_max = "style=90c9dd5c5a6af4ca5a62c0fac27a75403da1f915quot;max-width:" . $max_images . "px; width: [removed]this.width > " . $max_images . " ? " . $max_images . ": true);90c9dd5c5a6af4ca5a62c0fac27a75403da1f915quot;";
    endif;
    $str = preg_replace("'\[code\](.*?)\[/code\]'e", "parse_code_tag('$1')", $str);
    $find = array(
        '/\[code\](.*?)\[\/code\]/is',
        "'\[b\](.*?)\[/b\]'is",
        "'\[i\](.*?)\[/i\]'is",
        "'\[u\](.*?)\[/u\]'is",
        "'\[s\](.*?)\[/s\]'is",
        "'\[img\](.*?)\[/img\]'i",
        "'\[url\](.*?)\[/url\]'i",
        "'\[url=(.*?)\](.*?)\[/url\]'i",
        "'\[link\](.*?)\[/link\]'i",
        "'\[link=(.*?)\](.*?)\[/link\]'i",
        "'\[color=(.*?)\](.*?)\[/color\]'i",
    );

    $replace = array(
        '<pre class="code">\1</pre>',
        '<strong>\1</strong>',
        '<em>\1</em>',
        '<u>\1</u>',
        '<s>\1</s>',
        '<img src="\1" alt="" />',
        '<a href="\1">\1</a>',
        '<a href="\1">\2</a>',
        '<a href="\1">\1</a>',
        '<a href="\1">\2</a>',
        '<span style="color:\1">\2</span>',
    );

    return preg_replace($find, $replace, $str);
}