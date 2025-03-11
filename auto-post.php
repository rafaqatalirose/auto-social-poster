<?php

function fetchRSSFeed($feedUrl) {
    $rss = simplexml_load_file($feedUrl);
    if (!$rss) {
        die("RSS Feed load failed!");
    }
    return $rss;
}

function postToFacebook($postTitle, $postLink) {
    $fbPageUrl = 'https://www.facebook.com/profile.php?id=61554833731402';
    $message = urlencode("$postTitle\n\nRead more: $postLink");
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fbPageUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "message=$message");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

function postToPinterest($postTitle, $postLink, $imageUrl) {
    $pinBoardUrl = 'https://www.pinterest.com/aa4783116/movie-trailers-and-clips/';
    
    $pinData = [
        'title' => $postTitle,
        'link' => $postLink,
        'image_url' => $imageUrl,
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pinBoardUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($pinData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

$feedUrl = 'https://newvideo.great-site.net/feed/';
$rss = fetchRSSFeed($feedUrl);

$latestPost = $rss->channel->item[0];
$postTitle = (string) $latestPost->title;
$postLink = (string) $latestPost->link;
$postImage = (string) $latestPost->enclosure['url'];

$fbResponse = postToFacebook($postTitle, $postLink);
$pinResponse = postToPinterest($postTitle, $postLink, $postImage);

echo "Facebook Response: $fbResponse\n";
echo "Pinterest Response: $pinResponse\n";

?>

// Is script ko GitHub pe dal ke, GitHub Actions se har 30 min pe run kara sakte hain! 🚀
