<?php

switch ($_SERVER['HTTP_HOST']) {
    case 'www.vod-researchproject.info':
        include_once 'setting_researchproject.php';
        break;
    case 'www.educasy.com':
        include_once 'setting_educasy.php';
        break;
    case 'www.pec9.com':

        include_once 'setting_pec9_v2.php';
        break;
    case 'www.prokru.com':
        include_once 'prokru_v2.php';
        break;
    case '192.168.1.7':
        include_once 'setting_educasy_vm.php';
        break;
    case '127.0.0.1':
        include_once 'setting_educasy_vm.php';
        break;
    default:
        break;
}

