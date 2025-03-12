<?php

// === WordPress to Pinterest Auto-Poster (SSL Fixed) ===
// Secure, professional script with session handling, logging, and error management

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
        return file_get_contents($sessionFile);
    }
    logMessage('Session file not found. Please log in to Pinterest manually.');
    return false;
}

// Fetch posts from WordPress API (with SSL verification)
function fetchPosts($apiUrl) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 
    
    // Set CA bundle if needed
    $caBundlePath = __DIR__ . '/cacert.pem';
    if (file_exists($caBundlePath)) {
        curl_setopt($ch, CURLOPT_CAINFO, $caBundlePath);
    } else {
        logMessage('CA bundle not found. Skipping explicit CA path.');
    }

    $response = curl_exec($ch);
    if ($response === false) {
        logMessage('cURL Error: ' . curl_error($ch));
    }
    curl_close($ch);

    return json_decode($response, true);
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
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Cookie: ' . $session,
    ]);

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
    $title = $post['title']['rendered'];
    $link = $post['link'];
    postToPinterest($title, $link, $session);
}

logMessage('Script finished.');

?>

<!--
 🛠️ Ab kya karna hai?
1. 'pinterest_session.txt' me apni session cookie paste karein.
2. SSL ke liye 'cacert.pem' file download karein: https://curl.se/ca/cacert.pem
3. Is file ko script ke folder me daal kar dobara run karein: `php script.php`
4. Sab kuch smoothly run karega — errors log file me milenge.

Test karein aur mujhe update dein! 🚀
-->
