<?php

// === WordPress to Pinterest Auto-Poster (Optimized) ===

// Configuration
$wordpressApiUrl = 'https://newvideo.great-site.net/wp-json/wp/v2/posts';
$pinterestBoardUrl = 'https://www.pinterest.com/aa4783116/movie-trailers-and-clips/';
$sessionFile = 'pinterest_session.txt';
$logFile = 'auto-post.log';

// Helper function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Load session
function loadSession() {
    global $sessionFile;
    if (file_exists($sessionFile)) {
        return trim(file_get_contents($sessionFile));
    }
    logMessage('Session file not found. Please log in to Pinterest manually.');
    return false;
}

// Fetch posts from WordPress API with error handling
function fetchPosts($apiUrl) {
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
            'cafile' => __DIR__ . '/cacert.pem',
        ]
    ]);

    $response = @file_get_contents($apiUrl, false, $context);
    if ($response === false) {
        logMessage('Failed to fetch posts from WordPress API. Check SSL and API status.');
        return [];
    }
    
    $posts = json_decode($response, true);
    if (empty($posts)) {
        logMessage('API returned empty or invalid response.');
        return [];
    }
    
    return $posts;
}

// Post to Pinterest
function postToPinterest($postTitle, $postLink, $session) {
    $pinData = [
        'title' => $postTitle,
        'link' => $postLink,
    ];

    $ch = curl_init('https://www.pinterest.com/resource/PinResource/create/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pinData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Cookie: ' . $session]);
    curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        logMessage("Successfully posted to Pinterest: $postTitle");
    } else {
        logMessage("Failed to post to Pinterest. HTTP Code: $httpCode\nResponse: $response");
    }
}

// Main script logic
logMessage('Script started.');
$session = loadSession();
if (!$session) exit('Session not found. Check log for details.');

$posts = fetchPosts($wordpressApiUrl);
if (empty($posts)) exit('No posts found. Check log for details.');

foreach ($posts as $post) {
    $title = $post['title']['rendered'] ?? 'Untitled';
    $link = $post['link'] ?? '';
    
    if ($link) {
        postToPinterest($title, $link, $session);
    } else {
        logMessage("Skipping post without valid link: $title");
    }
}

logMessage('Script finished.');

?>

<!--
 🛠️ Setup steps:
1. Download 'cacert.pem' file and place it in your project directory.
2. Paste your Pinterest session cookie in 'pinterest_session.txt'.
3. Run the script: `php script.php`

If anything breaks, check the 'auto-post.log' file for errors! 🚀
-->
