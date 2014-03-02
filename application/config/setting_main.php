<?php

switch ($_SERVER['HTTP_HOST']) {
    case 'www.vod-researchproject.info':
        include_once 'setting_researchproject.php';
        break;
    case 'www.educasy.com':
        include_once 'setting_educasy.php';
        break;
    case '192.168.1.7':
        include_once 'setting_educasy_vm.php';
        break;
    default:
        break;
}

