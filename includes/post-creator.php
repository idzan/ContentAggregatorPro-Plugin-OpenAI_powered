<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Function to create a WordPress post programmatically
function cap_create_post($title, $content, $category = 'News', $tags = [], $featured_image_url = null) {
    // Check if a post with the same title already exists
    $existing_post = get_page_by_title($title, OBJECT, 'post');
    if ($existing_post) {
        return $existing_post->ID; // Return existing post ID
    }

    // Prepare the post data
    $post_data = [
        'post_title'   => wp_strip_all_tags($title),
        'post_content' => $content,
        'post_status'  => 'publish',
        'post_author'  => get_current_user_id(),
        'post_category' => [
            get_cat_ID($category) ?: wp_create_category($category)
        ],
        'tags_input'   => $tags,
    ];

    // Insert the post into the database
    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return false; // Return false if post creation failed
    }

    // Handle featured image if URL is provided
    if ($featured_image_url) {
        cap_set_featured_image($post_id, $featured_image_url);
        
        // Add attribution for the featured image source
        $attribution_text = '<p><small>Featured image source: <a href="' . esc_url($featured_image_url) . '" target="_blank">' . esc_html($featured_image_url) . '</a></small></p>';
        $post_content_with_attribution = $content . $attribution_text;
        wp_update_post([
            'ID' => $post_id,
            'post_content' => $post_content_with_attribution,
        ]);
    }

    return $post_id;
}

// Function to set a featured image for a post
function cap_set_featured_image($post_id, $image_url) {
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);

    if ($image_data) {
        $file_path = $upload_dir['path'] . '/' . $filename;
        file_put_contents($file_path, $image_data);

        $file_type = wp_check_filetype($filename, null);
        $attachment = [
            'post_mime_type' => $file_type['type'],
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];

        $attach_id = wp_insert_attachment($attachment, $file_path, $post_id);
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        wp_update_attachment_metadata($attach_id, $attach_data);
        set_post_thumbnail($post_id, $attach_id);
    }
}