<?php

function fetchPosts($apiUrl)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    if ($response === false) {
        echo "cURL Error: " . curl_error($ch) . "\n";
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        echo "Failed to fetch posts. HTTP Status Code: $httpCode\n";
        return null;
    }

    return json_decode($response, true);
}

$apiUrl = 'https://newvideo.great-site.net/wp-json/wp/v2/posts';
$posts = fetchPosts($apiUrl);

if ($posts && count($posts) > 0) {
    foreach ($posts as $post) {
        echo "Title: " . $post['title']['rendered'] . "\n";
        echo "Link: " . $post['link'] . "\n\n";
    }
} else {
    echo "No posts found or failed to fetch posts.\n";
}
?>
