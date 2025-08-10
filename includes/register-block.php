<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function wpr_register_reaction_block() {
    $asset_file = include( WPR_PLUGIN_DIR . 'build/index.asset.php');

    wp_register_script(
        'wpr-reaction-block-editor',
        WPR_PLUGIN_URL . 'build/index.js',
        $asset_file['dependencies'],
        $asset_file['version']
    );

    wp_register_style(
        'wpr-block-editor-style',
        WPR_PLUGIN_URL . 'assets/block-editor.css',
        [],
        filemtime( WPR_PLUGIN_DIR . 'assets/block-editor.css' )
    );

    register_block_type( 'wp-reactions/reaction-block', [
        'editor_script' => 'wpr-reaction-block-editor',
        'editor_style' => 'wpr-block-editor-style',
        'render_callback' => 'wpr_render_reaction_block',
    ] );
    
    wp_register_style( 'wpr-frontend-style', WPR_PLUGIN_URL . 'assets/style.css', [], filemtime( WPR_PLUGIN_DIR . 'assets/style.css' ) );
    wp_register_script( 'wpr-frontend-script', WPR_PLUGIN_URL . 'assets/frontend.js', ['jquery'], filemtime( WPR_PLUGIN_DIR . 'assets/frontend.js' ), true );
}

function wpr_render_reaction_block() {
    $post_id = get_the_ID();
    if ( ! $post_id ) {
        return '';
    }

    $emojis = wpr_get_emojis();
    if ( empty( $emojis ) ) {
        return '';
    }

    wp_enqueue_style( 'wpr-frontend-style' );
    wp_enqueue_script( 'wpr-frontend-script' );

    wp_localize_script( 'wpr-frontend-script', 'wpr_ajax_object', [
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'nonce'    => wp_create_nonce( 'wpr_reactions_nonce' ),
        'voted_message' => __( 'You have already voted for this article.', 'wp-reactions' ),
    ] );

    $output = '<div class="wpr-reactions-wrapper" data-post-id="' . esc_attr( $post_id ) . '">';
    
    foreach ( $emojis as $emoji ) {
        $count = get_post_meta( $post_id, 'wpr_reaction_' . $emoji, true ) ?: 0;
        $output .= sprintf(
            '<div class="wpr-reaction-item" data-emoji="%1$s" role="button" tabindex="0" aria-label="Tepki: %1$s">
                <span class="wpr-emoji">%1$s</span>
                <span class="wpr-reaction-count">%2$d</span>
            </div>',
            esc_attr( $emoji ),
            (int) $count
        );
    }
    
    $output .= '</div>';
    
    return $output;
}