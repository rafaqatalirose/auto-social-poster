<?php

// === WordPress to Pinterest Auto-Poster (Final Version) ===
// Secure script with API authentication, SSL handling, logging, and session management

// Configuration
$wordpressApiUrl = 'https://admin_username:application_password@hotviralhub.space/wp-json/wp/v2/posts';
$pinterestBoardUrl = 'https://www.pinterest.com/aa4783116/movie-trailers-and-clips/';
$sessionFile = 'pinterest_session.txt';
$logFile = 'auto-post.log';

// WordPress API Credentials
$apiUsername = 'hotviralhub'; // Aapka WP username
$apiPassword = 'AG&8oR9xXv'; // WP Application Password

// Helper function to log messages
function logMessage($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

// Load Pinterest session
function loadSession() {
    global $sessionFile;
    if (file_exists($sessionFile)) {
        return trim(file_get_contents($sessionFile));
    }
    logMessage('Session file not found. Please log in to Pinterest manually.');
    return false;
}

// Fetch posts from WordPress API using cURL
function fetchPosts($apiUrl, $username, $password) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_CAINFO, __DIR__ . '/cacert.pem');

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        logMessage('cURL error: ' . curl_error($ch));
        curl_close($ch);
        return [];
    }

    curl_close($ch);
    $posts = json_decode($response, true);

    if (empty($posts)) {
        logMessage('No posts found in WordPress API response.');
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

$posts = fetchPosts($wordpressApiUrl, $apiUsername, $apiPassword);
if (empty($posts)) exit('No posts found. Check log for details.');

foreach ($posts as $post) {
    if (!isset($post['title']['rendered'], $post['link'])) {
        logMessage('Invalid post data structure. Skipping post.');
        continue;
    }

    $title = strip_tags($post['title']['rendered']);
    $link = $post['link'];
    postToPinterest($title, $link, $session);
}

logMessage('Script finished.');

?>

<!--
 🛠️ Final Setup Steps:
1. WordPress Dashboard → Users → Your Profile → Generate Application Password.
2. Replace 'YOUR_WP_USERNAME' and 'YOUR_WP_APPLICATION_PASSWORD' above.
3. Save Pinterest session cookie in 'pinterest_session.txt'.
4. Run script: `php script.php`
5. Check 'auto-post.log' for success or errors.

Aap ka kaam ab asaan ho gaya! Agar koi dikkat aaye, to bas awaaz dena! 🚀
-->
