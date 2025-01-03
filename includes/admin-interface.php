<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add a settings page for the plugin
function cap_add_settings_page() {
    add_options_page(
        'Content Aggregator Settings',
        'Content Aggregator',
        'manage_options',
        'content-aggregator',
        'cap_render_settings_page'
    );
}
add_action('admin_menu', 'cap_add_settings_page');

// Enqueue admin scripts for tabs
function cap_admin_enqueue_scripts($hook) {
    if ($hook !== 'settings_page_content-aggregator') {
        return;
    }
    wp_enqueue_script('cap-tabs-script', plugin_dir_url(__FILE__) . 'assets/js/tabs.js', [], '1.0', true);
    wp_enqueue_style('cap-tabs-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css');
}
add_action('admin_enqueue_scripts', 'cap_admin_enqueue_scripts');

// Render the settings page with tabs
function cap_render_settings_page() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['cap_openai_api_key'])) {
            update_option('cap_openai_api_key', sanitize_text_field($_POST['cap_openai_api_key']));
        }
        if (isset($_POST['cap_gemini_api_key'])) {
            update_option('cap_gemini_api_key', sanitize_text_field($_POST['cap_gemini_api_key']));
        }
        if (isset($_POST['cap_feed_sources'])) {
            $feeds = array_map('sanitize_text_field', $_POST['cap_feed_sources']);
            update_option('cap_feed_sources', array_filter($feeds));
        }
        if (isset($_POST['cap_feed_categories'])) {
            update_option('cap_feed_categories', array_map('sanitize_text_field', explode(',', $_POST['cap_feed_categories'])));
        }
        if (isset($_POST['cap_cron_interval'])) {
            update_option('cap_cron_interval', sanitize_text_field($_POST['cap_cron_interval']));
        }
        echo '<div class="updated"><p>Settings saved successfully.</p></div>';
    }

    $openai_api_key = get_option('cap_openai_api_key', '');
    $gemini_api_key = get_option('cap_gemini_api_key', '');
    $feed_sources = get_option('cap_feed_sources', []);
    $feed_categories = implode(', ', get_option('cap_feed_categories', []));
    $cron_interval = get_option('cap_cron_interval', 'hourly');

    echo '<div class="wrap">';
    echo '<h1>Content Aggregator Settings</h1>';

    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="#tab-api" class="nav-tab nav-tab-active">API Keys</a>';
    echo '<a href="#tab-feeds" class="nav-tab">Feeds & Categories</a>';
    echo '<a href="#tab-cron" class="nav-tab">Cron Job (Advanced)</a>';
    echo '</h2>';

    echo '<div id="tab-api" class="tab-content" style="display: block;">';
    echo '<h2>API Keys</h2>';
    echo '<form method="post">';
    echo '<label for="cap_openai_api_key">OpenAI API Key:</label>'; 
    echo '<input type="text" id="cap_openai_api_key" name="cap_openai_api_key" value="' . esc_attr($openai_api_key) . '" style="width: 100%;" placeholder="Enter your OpenAI API Key">';
    echo '<p class="description">Enter your OpenAI API key to enable content summarization and rewriting.</p>';

    echo '<label for="cap_gemini_api_key">Gemini API Key:</label>'; 
    echo '<input type="text" id="cap_gemini_api_key" name="cap_gemini_api_key" value="' . esc_attr($gemini_api_key) . '" style="width: 100%;" placeholder="Enter your Gemini API Key">';
    echo '<p class="description">Enter your Gemini API key to enable content summarization using Google Gemini.</p>';
    echo '</form>';
    echo '</div>';

    echo '<div id="tab-feeds" class="tab-content" style="display: none;">';
    echo '<h2>Feeds & Categories</h2>';
    echo '<form method="post">';
    if (!empty($feed_sources)) {
        foreach ($feed_sources as $index => $source) {
            echo '<input type="text" name="cap_feed_sources[]" value="' . esc_attr($source) . '" style="width: 100%; margin-bottom: 5px;" placeholder="Enter RSS Feed URL">';
        }
    }
    echo '<input type="text" name="cap_feed_sources[]" value="" style="width: 100%; margin-bottom: 5px;" placeholder="Enter RSS Feed URL">';
    echo '<p class="description">Add or remove RSS feed URLs. Each URL should point to a valid RSS feed from which posts will be aggregated.</p>';

    echo '<textarea name="cap_feed_categories" rows="5" style="width: 100%;" placeholder="Enter categories to rewrite, separated by commas.">$0</textarea><p class="description">Specify categories from the feeds to rewrite. Categories should match the names provided in the RSS feed or website source. For example:</p><ul class="description"><li><strong>From RSS feed:</strong> Technology, Business</li><li><strong>From source site:</strong> Server Infrastructure, Cloud Computing</li></ul><p class="description">Ensure these categories are correctly spelled and separated by commas.</p>';
    echo '<p class="description">Specify categories from the feeds to rewrite. Categories should match the names provided in the RSS feed or website source. Ensure these categories are correctly spelled and separated by commas.</p>';
    echo '</form>';
    echo '</div>';

    echo '<div id="tab-cron" class="tab-content" style="display: none;">';
    echo '<h2>Cron Job (Advanced)</h2>';
    echo '<form method="post">';
    echo '<select name="cap_cron_interval">
        <option value="hourly"' . selected($cron_interval, 'hourly', false) . '>Hourly</option>
        <option value="every_12_hours"' . selected($cron_interval, 'every_12_hours', false) . '>Every 12 Hours</option>
        <option value="every_24_hours"' . selected($cron_interval, 'every_24_hours', false) . '>Every 24 Hours</option>
    </select>
    <p class="description">Choose how often the plugin checks for new content.</p>';
    echo '</form>';
    echo '</div>';

    echo '</div>';
}

?>
