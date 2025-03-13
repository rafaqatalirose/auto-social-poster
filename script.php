<?php

function fetchPosts($apiUrl) {
    // Stream context for SSL
    $context = stream_context_create([
        'http' => [
            'ignore_errors' => true
        ],
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
        ],
    ]);

    // Fetching data from API
    $response = file_get_contents($apiUrl, false, $context);

    if ($response === false) {
        echo "API call failed!";
    } else {
        echo "API Response: " . $response;
    }

    // Decode JSON response
    $data = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON decode error: " . json_last_error_msg();
    } else {
        echo "JSON decoded successfully!\n";
        
        // Loop through posts
        if (!empty($data)) {
            foreach ($data as $post) {
                echo "Title: " . $post['title']['rendered'] . "\n";
                echo "Link: " . $post['link'] . "\n\n";
            }
        } else {
            echo "No posts found!";
        }
    }
}

// API URL
$apiUrl = 'https://newvideo.great-site.net/wp-json/wp/v2/posts';
fetchPosts($apiUrl);

?>
