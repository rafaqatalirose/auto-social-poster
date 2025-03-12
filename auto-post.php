<?php

// === WordPress to Pinterest Auto-Poster === //

// Load environment variables (GitHub Secrets)
$wp_api_url = getenv('WP_API_URL');
$pinterest_email = getenv('PINTEREST_EMAIL');
$pinterest_password = getenv('PINTEREST_PASSWORD');
$pinterest_board = getenv('PINTEREST_BOARD');

// Function to fetch WordPress posts
function fetch_wp_posts($api_url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code !== 200) {
        echo "Failed to fetch posts from WordPress API. HTTP Status Code: $http_code\n";
        exit;
    }

    curl_close($ch);
    return json_decode($response, true);
}

// Function to post to Pinterest
function post_to_pinterest($email, $password, $board, $post)
{
    $login_url = 'https://www.pinterest.com/login/';
    
    $pin_url = 'https://www.pinterest.com/resource/PinResource/create/';
    
    // Placeholder for actual Pinterest API request
    // You’ll replace this with a real API call or scraping logic
    echo "Posting to Pinterest:\n";
    echo "Board: $board\n";
    echo "Title: " . $post['title']['rendered'] . "\n";
    echo "Link: " . $post['link'] . "\n";
    echo "Image: " . $post['yoast_head_json']['og_image'][0]['url'] . "\n";
}

// Main logic
$posts = fetch_wp_posts($wp_api_url);

foreach ($posts as $post) {
    post_to_pinterest($pinterest_email, $pinterest_password, $pinterest_board, $post);
    sleep(5); // Thodi delay taake Pinterest block na kare
}

?>
