<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function wpr_save_reaction_ajax_handler() {
    check_ajax_referer( 'wpr_reactions_nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    $emoji = isset( $_POST['emoji'] ) ? sanitize_text_field( $_POST['emoji'] ) : '';

    if ( ! $post_id || empty( $emoji ) ) {
        wp_send_json_error( [ 'message' => 'Bad Request.' ] );
    }
    
    // IP Control
    $user_ip = wpr_get_user_ip();
    $voted_ips = get_post_meta( $post_id, '_wpr_voted_ips', true );
    if ( ! is_array( $voted_ips ) ) {
        $voted_ips = [];
    }
    
    if ( in_array( $user_ip, $voted_ips ) ) {
        wp_send_json_error( [ 'message' => 'You have already voted for this article.' ] );
    }

    // Save Vote
    $current_count = get_post_meta( $post_id, 'wpr_reaction_' . $emoji, true ) ?: 0;
    $new_count = (int) $current_count + 1;
    update_post_meta( $post_id, 'wpr_reaction_' . $emoji, $new_count );
    
    // IP Save
    $voted_ips[] = $user_ip;
    update_post_meta( $post_id, '_wpr_voted_ips', $voted_ips );
    
    wp_send_json_success( [ 'new_count' => $new_count ] );
}