<?php

function str_rand($length = 8, $seeds = 'alphanum') {
    // Possible seeds
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
    $seedings['hexidec'] = '0123456789abcdef';

    // Choose seed
    if (isset($seedings[$seeds])) {
        $seeds = $seedings[$seeds];
    }

    // Seed generator
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);

    // Generate
    $str = '';
    $seeds_count = strlen($seeds);

    for ($i = 0; $length > $i; $i++) {
        $str .= $seeds{mt_rand(0, $seeds_count - 1)};
    }

    return $str;
}