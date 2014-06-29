<?php

switch ($_SERVER['HTTP_HOST']) {
    case 'www.vod-researchproject.info':
        include_once 'setting_vod.php';
        break;
    
    default:
        include_once 'setting_vod.php';
        break;
}

