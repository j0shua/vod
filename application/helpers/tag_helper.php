<?php

function encode_tags($tags) {
    $a_tags = array();
    $tags = explode(',', $tags);
    foreach ($tags as $v) {
        $a_tags[] = trim($v);
    }
    $a_tags = array_unique($a_tags);
    return implode(', ', $a_tags);
}