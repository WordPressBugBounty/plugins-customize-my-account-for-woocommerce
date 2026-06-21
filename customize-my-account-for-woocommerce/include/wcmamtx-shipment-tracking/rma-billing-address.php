<?php
// Append billing address fields to rmaData for Stripe confirmSetup
add_filter('script_loader_tag', function($tag, $handle) {
    if ($handle !== 'rma-app') return $tag;
    $uid = get_current_user_id();
    if (!$uid) return $tag;
    $country     = get_user_meta($uid, 'billing_country', true);
    $state       = get_user_meta($uid, 'billing_state', true);
    $city        = get_user_meta($uid, 'billing_city', true);
    $postal_code = get_user_meta($uid, 'billing_postcode', true);
    $line1       = get_user_meta($uid, 'billing_address_1', true);
    $line2       = get_user_meta($uid, 'billing_address_2', true);
    $phone       = get_user_meta($uid, 'billing_phone', true);
    $data = json_encode([
        'country'     => $country ?: null,
        'state'       => $state ?: null,
        'city'        => $city ?: null,
        'postal_code' => $postal_code ?: null,
        'line1'       => $line1 ?: null,
        'line2'       => $line2 ?: null,
        'phone'       => $phone ?: null,
    ]);
    $inline = "<script>if(window.rmaData){rmaData.billingAddress=" . $data . ";}</script>";
    return $tag . $inline;
}, 20, 2);
