<?php

function fetchPosts($apiUrl)
{
    $cookieFile = 'cookies.txt';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFile);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFile);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);

    if ($httpCode !== 200) {
        echo "Failed to fetch posts. HTTP Status Code: $httpCode\n";
        return null;
    }

    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON decode error: " . json_last_error_msg() . "\n";
        return null;
    }

    return $data;
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

// اس script کو چلائیں، اور cookies handle کرنے کے بعد دوبارہ API سے صحیح response آنا چاہیے۔
// اگر کوئی اور error آئے تو بتائیں تاکہ ہم اسے بھی fix کر سکیں! 🚀
