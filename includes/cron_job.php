<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Schedule the cron job on plugin activation
function cap_schedule_cron_job() {
    $interval = get_option('cap_cron_interval', 'hourly'); // Default to hourly if not set
    if (!wp_next_scheduled('cap_fetch_and_post_content')) {
        wp_schedule_event(time(), $interval, 'cap_fetch_and_post_content');
    }
}
register_activation_hook(__FILE__, 'cap_schedule_cron_job');

// Clear the cron job on plugin deactivation
function cap_clear_cron_job() {
    $timestamp = wp_next_scheduled('cap_fetch_and_post_content');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'cap_fetch_and_post_content');
    }
}
register_deactivation_hook(__FILE__, 'cap_clear_cron_job');

// Define the callback function for the cron job
add_action('cap_fetch_and_post_content', 'cap_execute_cron_job');
function cap_execute_cron_job() {
    // Fetch RSS feed sources from settings
    $feed_sources = get_option('cap_feed_sources', []);
    $categories_to_rewrite = get_option('cap_feed_categories', []);

    if (empty($feed_sources)) {
        return; // No feeds configured
    }

    // Fetch and process content from each feed
    foreach ($feed_sources as $feed_url) {
        $items = cap_fetch_feeds([$feed_url]);
        $filtered_items = array_filter($items, function ($item) use ($categories_to_rewrite) {
            $item_categories = array_map(function ($cat) {
                return strtolower($cat->get_label());
            }, $item['categories'] ?? []);

            return array_intersect($categories_to_rewrite, $item_categories);
        });

        foreach ($filtered_items as $item) {
            $combined_content = cap_combine_content([$item]);

            cap_create_post(
                $item['title'],
                $combined_content,
                'News', // Default category, changeable based on your settings
                [], // No specific tags for now
                $item['link'] // Assume featured image comes from the item's link for demo purposes
            );
        }
    }
}

// Add custom intervals for cron jobs
add_filter('cron_schedules', 'cap_add_custom_intervals');
function cap_add_custom_intervals($schedules) {
    $schedules['every_12_hours'] = [
        'interval' => 12 * HOUR_IN_SECONDS,
        'display' => __('Every 12 Hours', 'content-aggregator')
    ];
    $schedules['every_24_hours'] = [
        'interval' => 24 * HOUR_IN_SECONDS,
        'display' => __('Every 24 Hours', 'content-aggregator')
    ];
    return $schedules;
}
