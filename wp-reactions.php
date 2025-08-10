<?php
/**
 * Plugin Name:       WP Reactions
 * Description:       A Gutenberg plugin that lets you add voteable "Emoji Reactions" to the end of your posts. See the reactions your posts receive with voteable emojis. Emojis can be changed, added, or removed from the Settings menu.
 * Version:           1.0.8
 * Author:            mabaci
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       wp-reactions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

define( 'WPR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once WPR_PLUGIN_DIR . 'includes/helpers.php';
require_once WPR_PLUGIN_DIR . 'admin/settings-page.php';
require_once WPR_PLUGIN_DIR . 'includes/register-block.php';
require_once WPR_PLUGIN_DIR . 'includes/save-reaction.php';

add_action( 'admin_menu', 'wpr_add_admin_menu' );
add_action( 'admin_init', 'wpr_settings_init' );
add_action( 'init', 'wpr_register_reaction_block' );
add_action( 'wp_ajax_wpr_save_reaction', 'wpr_save_reaction_ajax_handler' );
add_action( 'wp_ajax_nopriv_wpr_save_reaction', 'wpr_save_reaction_ajax_handler' ); 