<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function wpr_add_admin_menu() {
    add_options_page(
        'WP Reactions Settings',
        'WP Reactions',
        'manage_options',
        'wp-reactions',
        'wpr_settings_page_html'
    );
}

function wpr_settings_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'wpr_settings_group' );
            do_settings_sections( 'wp-reactions' );
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}

function wpr_settings_init() {
    register_setting( 'wpr_settings_group', 'wpr_emoji_list', 'sanitize_text_field' );

    add_settings_section(
        'wpr_settings_section',
        'Emoji Settings',
        null,
        'wp-reactions'
    );

    add_settings_field(
        'wpr_emoji_list_field',
        'Emoji List',
        'wpr_emoji_list_field_cb',
        'wp-reactions',
        'wpr_settings_section'
    );
}

function wpr_emoji_list_field_cb() {
    $emojis = get_option( 'wpr_emoji_list', '😍,🔥,😆,👍,👎,😡,😯,🙏' );
    ?>
    <textarea name="wpr_emoji_list" rows="3" cols="50" class="large-text"><?php echo esc_textarea( $emojis ); ?></textarea>
    <p class="description">Add or delete Emojis by separating them with commas. 💁,👌,🎍,😍, 🫂,🤍,🫶🏻,🎀,❤️‍🩹,✅,🙏,😭,💔,😂,🤣,😆,😅,😜,😝,🤪,😏,😎,😤</p>
    <?php
}