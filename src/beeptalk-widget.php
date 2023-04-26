<?php
/*
Plugin Name: Beeptalk Widget
Plugin URI: https://beeptalk.app/wp-plugin
Description: Support chatbot service powered by ChatGPT natural language processing AI, bring your customer service to the next level and provide automatic human-like support to your clients.
Author: Beeptalk
Author URI: https://beeptalk.app/
Version: 1.0.0
License: GPL-3.0
License URI:  https://www.gnu.org/licenses/gpl-3.0.html
*/

// Add the plugin settings page
add_action('admin_menu', 'beeptalk_widget_settings_page');
function beeptalk_widget_settings_page() {
    add_menu_page(
        'Beeptalk Widget Settings',
        'Beeptalk Widget Settings',
        'manage_options',
        'beeptalk-widget-settings',
        'beeptalk_widget_settings_page_callback'
    );
}

// Register the plugin settings
add_action('admin_init', 'register_beeptalk_widget_settings');
function register_beeptalk_widget_settings() {
    register_setting('beeptalk-widget-settings-group', 'beeptalk_project_id');
    register_setting('beeptalk-widget-settings-group', 'beeptalk_agent');
}

// Callback function for the plugin settings page
function beeptalk_widget_settings_page_callback() {
    ?>
    <div class="wrap">
        <h1>Beeptalk Widget Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields('beeptalk-widget-settings-group'); ?>
            <?php do_settings_sections('beeptalk-widget-settings-group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Beeptalk project ID</th>
                    <td><input type="text" name="beeptalk_project_id" value="<?php echo esc_attr(get_option('beeptalk_project_id')); ?>" required /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Force agent email (optional)</th>
                    <td><input type="text" name="beeptalk_agent" value="<?php echo esc_attr(get_option('beeptalk_agent')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Insert the custom script
function insert_beeptalk_widget_script() {
    $id = get_option('beeptalk_project_id');
    if (!empty($id)) {
        $user = wp_get_current_user();
        $user_first_name = $user->first_name;
        $uuid = get_current_user_id();
        $suid = NULL;
        if (isset($_COOKIE['wordpress_logged_in_'])) {
            $cookie = $_COOKIE['wordpress_logged_in_'];
            $suid = substr($cookie, 0, 64);
        }
        $agent = get_option('beeptalk_agent');
        
        echo '<script src="https://cdn.jsdelivr.net/gh/beeptalk-app/beeptalk-widget@latest/index.min.js"></script>';
        echo '<script>';
        echo 'beeptalkInit({';
        echo "id: '$id',";
        if (!empty($uuid) && !is_null($uuid) && isset($uuid)) {
            echo "uuid: '$uuid',";
        }
        if (!empty($suid) && !is_null($suid) && isset($suid)) {
            echo "suid: '$suid',";
        }
        if (!empty($user_first_name) && !is_null($user_first_name) && isset($user_first_name)) {
            echo "uname: '$user_first_name',";
        }
        if (!empty($agent) && !is_null($agent) && isset($agent)) {
            echo "agent: '$agent',";
        }
        echo '});';
        echo '</script>';
    }
}
add_action( 'wp_footer', 'insert_beeptalk_widget_script' );
?>
