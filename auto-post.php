<?php

// RSS Feed URL
$rss_feed_url = 'https://newvideo.great-site.net/feed/';

// Pinterest URL
$pinterest_url = 'https://www.pinterest.com/aa4783116/movie-trailers-and-clips/';

// Fetch RSS Feed with SSL bypass
try {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $rss_feed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $rss_data = curl_exec($ch);
    curl_close($ch);

    if (!$rss_data) {
        throw new Exception("RSS feed load failed.");
    }

    $rss = simplexml_load_string($rss_data);
    if (!$rss) {
        throw new Exception("Failed to parse RSS feed.");
    }

    $latest_item = $rss->channel->item[0];
    $post_title = (string) $latest_item->title;
    $post_link = (string) $latest_item->link;
    $post_description = strip_tags((string) $latest_item->description);
    
    // Generate hashtags from categories
    $categories = [];
    foreach ($latest_item->category as $category) {
        $categories[] = '#' . preg_replace('/\s+/', '', (string) $category);
    }
    $hashtags = implode(' ', $categories);

    $message = "🆕 New Video Alert: $post_title\n🔗 Watch here: $post_link\n\n📌 Also shared on Pinterest!\n$hashtags";

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

// Ab bas ye script GitHub pe save karke ek cron job ya manual run setup karna hai. 🚀
