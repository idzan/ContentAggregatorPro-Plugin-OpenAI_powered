<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to summarize content using OpenAI for the content combiner
function cap_openai_summarize_logic($content, $api_key) {
    $url = 'https://api.openai.com/v1/completions';
    
    // Prepare the API request payload
    $data = [
        'model' => 'text-davinci-003',
        'prompt' => "Summarize the following content concisely:\n\n$content",
        'max_tokens' => 200,
        'temperature' => 0.7
    ];

    // Prepare the request arguments
    $args = [
        'body' => json_encode($data),
        'headers' => [
            'Content-Type' => 'application/json',
            'Authorization' => "Bearer $api_key",
        ],
    ];

    // Send the request to OpenAI
    $response = wp_remote_post($url, $args);

    // Check for errors
    if (is_wp_error($response)) {
        return $content; // Return original content on error
    }

    // Decode the response body
    $body = json_decode(wp_remote_retrieve_body($response), true);

    // Return the summarized text or the original content if summarization fails
    return $body['choices'][0]['text'] ?? $content;
}

// Example usage (if needed for testing):
// $api_key = 'your_openai_api_key_here';
// $content = 'This is a long piece of content that needs to be summarized.';
// echo cap_openai_summarize_logic($content, $api_key);