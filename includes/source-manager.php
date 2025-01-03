<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to fetch and parse RSS feeds
function cap_fetch_feeds($feed_urls = []) {
    if (empty($feed_urls)) {
        return [];
    }

    $all_items = [];

    foreach ($feed_urls as $url) {
        $rss = fetch_feed($url);

        if (is_wp_error($rss)) {
            continue;
        }

        $max_items = $rss->get_item_quantity();
        $rss_items = $rss->get_items(0, $max_items);

        foreach ($rss_items as $item) {
            $all_items[] = [
                'title' => $item->get_title(),
                'link' => $item->get_permalink(),
                'date' => $item->get_date('Y-m-d H:i:s'),
                'content' => $item->get_content(),
                'categories' => $item->get_categories(),
            ];
        }
    }

    return $all_items;
}

// Example usage (for debugging or testing purposes):
// $feed_urls = ['https://example.com/feed', 'https://anotherexample.com/feed'];
// $items = cap_fetch_feeds($feed_urls);
// print_r($items);