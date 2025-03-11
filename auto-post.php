<?php

// RSS Feed URL
$rss_feed_url = 'https://newvideo.great-site.net/feed/';

// Facebook and Pinterest URLs
$facebook_url = 'https://www.facebook.com/profile.php?id=61554833731402';
$pinterest_url = 'https://www.pinterest.com/aa4783116/movie-trailers-and-clips/';

// Fetch RSS Feed
try {
    $rss = simplexml_load_file($rss_feed_url);
    if (!$rss) {
        throw new Exception("RSS feed load failed.");
    }

    $latest_item = $rss->channel->item[0];
    $post_title = $latest_item->title;
    $post_link = $latest_item->link;
    $post_description = strip_tags($latest_item->description);

    $message = "🆕 New Video Alert: $post_title\n🔗 Watch here: $post_link\n\n📌 Also shared on Pinterest!";

    // Post to Facebook (Using cURL)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $facebook_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['message' => $message]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo "Facebook post done!\n";

    // Post to Pinterest (Using cURL)
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $pinterest_url);
    curl_setopt($ch2, CURLOPT_POST, 1);
    curl_setopt($ch2, CURLOPT_POSTFIELDS, ['message' => $message]);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    $response2 = curl_exec($ch2);
    curl_close($ch2);

    echo "Pinterest post done!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>


// Save aur commit karne ke baad main workflow file ka code bhi de dunga! 🚀
