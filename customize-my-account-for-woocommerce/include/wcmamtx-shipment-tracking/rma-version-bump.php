<?php
// Force browser cache bust for the fixed myaccount.js
add_filter('script_loader_src', function($src, $handle) {
    if ($handle === 'rma-app') {
        $src = add_query_arg('ver', '2.1.6', remove_query_arg('ver', $src));
    }
    return $src;
}, 999, 2);
