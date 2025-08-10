<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get Emoji List
 * @return array
 */
function wpr_get_emojis() {
    $default_emojis = '😍,🔥,😆,👍,👎,😡,😯,🙏';
    $emojis_string = get_option('wpr_emoji_list', $default_emojis);

    $emojis_array = array_map('trim', explode(',', $emojis_string));
    return array_filter($emojis_array); 
}

/**
 * Get IP Address
 * @return string
 */
function wpr_get_user_ip() {
    if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return apply_filters( 'wpr_get_user_ip', $ip );
}