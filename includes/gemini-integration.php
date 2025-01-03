<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to summarize content using Google Gemini API for the content combiner
function cap_gemini_summarize_logic($content, $api_key) {
    $url = 'https://gemini.googleapis.com/v1/summarize'; // Placeholder URL; replace with the actual Gemini API URL

    // Prepare the API request payload
    $data = [
        'content' => $content,
        'length' => 'short'
    ];

    // Prepare the request arguments
    $args = [
        'body' => json_encode($data),
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $api_key",
        ],
    ];

    // Send the request to Google Gemini API
    $response = wp_remote_post($url, $args);

    // Check for errors
    if (is_wp_error($response)) {
        return $content; // Return original content on error
    }

    // Decode the response body
    $body = json_decode(wp_remote_retrieve_body($response), true);

    // Return the summarized text or the original content if summarization fails
    return $body['summary'] ?? $content;
}

// Example usage (if needed for testing):
// $api_key = 'your_gemini_api_key_here';
// $content = 'This is a long piece of content that needs to be summarized.';
// echo cap_gemini_summarize_logic($content, $api_key);