<?php

$parse_base_url = parse_url($base_url);
if (isset($parse_base_url['query'])) {
    $base_url = $base_url . '&';
} else {
    $base_url = $base_url . '?';
}
if ($total_rows > 0) {


    $total_pages = ceil($total_rows / $per_page);
    $pages = range(1, $total_pages);
    $a_page_str = array();
    $back_url = '';
    $next_url = '';
    $first_url = '';
    $last_url = '';
    foreach ($pages as $page) {
        if ($current_page == $page) {
            $a_page_str[] = $page;
            if ($current_page != 1) {
                $back_url = anchor($base_url . 'page=' . ($page - 1), '< ');

                $first_url = anchor($base_url . 'page=1', '« First ');
            }
            if ($current_page != $total_pages) {
                $next_url = anchor($base_url . 'page=' . ($page + 1), ' > ');
                $last_url = anchor($base_url . 'page=' . $total_pages, 'Last »');
            }
        } else {
            $a_page_str[] = anchor($base_url . 'page=' . $page, $page);
        }
    }

    echo $first_url . $back_url . implode(' ', $a_page_str) . $next_url . $last_url;
}
?>