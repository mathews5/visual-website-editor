<?php

/**
 * Plugin Name: Visual Website Editor
 * Plugin URI: http://www.tidioelements.com
 * Description: Visual Website Editor for WordPress
 * Version: 1.0.3
 * Author: Tidio Ltd.
 * Author URI: http://www.tidiomobile.com
 * License: GPL2
 */
class TidioVisualEditor {

    /**
     * Id of plugin option page.
     * @var string
     */
    public $page_id;
    private $script_path = '//tidioelements.com/redirect/';

    /**
     * Start up
     */
    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('wp_enqueue_scripts', array($this, 'theme_scripts'));
        add_action('wp_ajax_tidio_visual_set_key', array($this, 'ajax_set_key'));
    }

    function ajax_set_key() {
        global $wpdb;

        $key = $_POST['key'];
        $option_name = 'tidio-visual-public-key';
        $re = update_option($option_name, $key);
        return json_encode(array('success' => true));
    }

    /**
     * Adds help tab on option page.
     */
    public function help_tab() {
        $screen = get_current_screen();
        /*
         * Check if current screen is ok
         * Don't add help tab if it's not
         */
        if ($screen->id != $this->page_id)
            return;
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
        $option_page = add_menu_page(
                'Visual Editor', 'Visual Editor', 'manage_options', 'tidio-visual-editor', array($this, 'create_admin_page'), plugins_url(basename(__DIR__) . '/media/img/icon.png')
        );
        $this->page_id = $option_page;
    }

    /**
     * Options page callback
     */
    public function create_admin_page() {
        // Set class property
        $dir = plugin_dir_path(__FILE__);
        include $dir . 'options.php';
    }

    /**
     * Register and add settings
     */
    public function theme_scripts() {
        $option_name = 'tidio-visual-public-key';
        $option_value = get_option($option_name);
        if (!empty($option_value))
            wp_enqueue_script('tidio-elements', $this->script_path . $option_value . '.js', array(), '1.0', false);
    }

}

/**
 * Create new instance of plugin class
 */
$my_settings_page = new TidioVisualEditor();