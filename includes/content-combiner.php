<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to combine and summarize content from multiple sources
function cap_combine_content($items) {
    if (empty($items)) {
        return '';
    }

    $combined_content = '';
    $sources = [];

    foreach ($items as $item) {
        $combined_content .= "<h2>" . esc_html($item['title']) . "</h2>";
        $combined_content .= "<p>" . esc_html(wp_trim_words($item['content'], 50, '...')) . "</p>";
        $sources[] = $item['link'];
    }

    // Summarize the combined content using the selected API
    $summarized_content = cap_summarize_content($combined_content);

    // Append source links
    $source_links = '<h3>Sources:</h3><ul>';
    foreach ($sources as $source) {
        $source_links .= '<li><a href="' . esc_url($source) . '" target="_blank">' . esc_html($source) . '</a></li>';
    }
    $source_links .= '</ul>';

    return $summarized_content . $source_links;
}

// Function to summarize content using the selected API
function cap_summarize_content($content) {
    $openai_api_key = get_option('na_openai_api_key');
    $gemini_api_key = get_option('na_gemini_api_key');

    if (!empty($gemini_api_key)) {
        return cap_gemini_summarize($content, $gemini_api_key);
    } elseif (!empty($openai_api_key)) {
        return cap_openai_summarize($content, $openai_api_key);
    }

    return $content; // Return original content if no API key is set
}

// Function to summarize content using OpenAI
function cap_openai_summarize($content, $api_key) {
    // The logic for OpenAI is moved to openai-integration.php
    return cap_openai_summarize_logic($content, $api_key);
}

// Function to summarize content using Google Gemini API
function cap_gemini_summarize($content, $api_key) {
    // The logic for Google Gemini is moved to gemini-integration.php
    return cap_gemini_summarize_logic($content, $api_key);
}

// Example usage (for debugging or testing purposes):
// $items = [
//     ['title' => 'Title 1', 'content' => 'Content 1', 'link' => 'https://example.com/1'],
//     ['title' => 'Title 2', 'content' => 'Content 2', 'link' => 'https://example.com/2']
// ];
// echo cap_combine_content($items);