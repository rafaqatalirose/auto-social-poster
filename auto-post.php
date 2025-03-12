<?php

// Auto-post WordPress to Pinterest Script
// Safe, session-based, API-free solution

// Config
$wordpressApiUrl = 'https://newvideo.great-site.net/wp-json/wp/v2/posts';
$pinterestBoardUrl = 'https://www.pinterest.com/aa4783116/movie-trailers-and-clips/';
$sessionFile = 'pinterest_session.txt';

// Function to fetch posts from WordPress API
function fetchWordPressPosts($apiUrl)
{
    $response = file_get_contents($apiUrl);
    if ($response === FALSE) {
        echo "Failed to fetch posts from WordPress API.\n";
        return [];
    }
    return json_decode($response, true);
}

// Function to post to Pinterest
function postToPinterest($title, $link, $image, $sessionFile, $boardUrl)
{
    $sessionCookie = file_get_contents($sessionFile);
    if (!$sessionCookie) {
        echo "Pinterest session cookie missing. Please login manually and save session.\n";
        return false;
    }

    $postData = [
        'title' => $title,
        'url' => $link,
        'image_url' => $image,
        'description' => $title . ' — Watch Now!',
    ];

    $ch = curl_init($boardUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Cookie: ' . trim($sessionCookie),
        'User-Agent: Mozilla/5.0',
    ]);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo "Post successful: $title\n";
        return true;
    } else {
        echo "Failed to post: $title. HTTP Code: $httpCode\n";
        return false;
    }
}

// Fetch posts and pin them
$posts = fetchWordPressPosts($wordpressApiUrl);
if (empty($posts)) {
    echo "No posts found to publish.\n";
    exit;
}

foreach ($posts as $post) {
    $title = $post['title']['rendered'];
    $link = $post['link'];
    $image = $post['jetpack_featured_media_url'] ?? '';

    if ($image && postToPinterest($title, $link, $image, $sessionFile, $pinterestBoardUrl)) {
        echo "Pinned: $title\n";
    } else {
        echo "Skipping: $title (No image or failed to post)\n";
    }
}

?>

// Next step: Save Pinterest session manually and upload the session file!
// Let me know, I’ll guide you through it. 🚀
