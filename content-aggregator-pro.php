<?php
/*
Plugin Name: Content Aggregator Pro
Plugin URI: https://techbrief.news
Description: A WordPress plugin for aggregating and rewriting content from multiple sources for niche topics.
Version: 1.0
Author: Marko Idzan
Author URI: https://idzan.hr
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants for the plugin
if (!defined('CAP_PLUGIN_DIR')) {
    define('CAP_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('CAP_PLUGIN_URL')) {
    define('CAP_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Include core files
require_once CAP_PLUGIN_DIR . 'includes/source-manager.php';
require_once CAP_PLUGIN_DIR . 'includes/content-combiner.php';
require_once CAP_PLUGIN_DIR . 'includes/openai-integration.php';
require_once CAP_PLUGIN_DIR . 'includes/gemini-integration.php';
require_once CAP_PLUGIN_DIR . 'includes/post-creator.php';
require_once CAP_PLUGIN_DIR . 'includes/admin-interface.php';
require_once CAP_PLUGIN_DIR . 'includes/cron-job.php';

// Enqueue admin styles and scripts
function cap_admin_enqueue_assets($hook) {
    if ($hook !== 'settings_page_content-aggregator') {
        return;
    }
    wp_enqueue_script('cap-tabs-script', CAP_PLUGIN_URL . 'assets/js/tabs.js', [], '1.0', true);
    wp_enqueue_style('cap-admin-style', CAP_PLUGIN_URL . 'assets/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'cap_admin_enqueue_assets');

// Activation hook to initialize cron jobs
function cap_activate() {
    cap_schedule_cron_job();
}
register_activation_hook(__FILE__, 'cap_activate');

// Deactivation hook to clear cron jobs
function cap_deactivate() {
    cap_clear_cron_job();
}
register_deactivation_hook(__FILE__, 'cap_deactivate');
