<?php

// RSS Feed URL
$rss_feed_url = 'https://newvideo.great-site.net/feed/';

// Pinterest Board URL
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
    
    // Generate hashtags from categories
    $hashtags = [];
    foreach ($latest_item->category as $category) {
        $hashtags[] = '#' . str_replace(' ', '', $category);
    }
    $hashtags_string = implode(' ', $hashtags);

    $message = "🆕 New Video Alert: $post_title\n🔗 Watch here: $post_link\n\n$hashtags_string\n📌 Auto-shared on Pinterest!";

    // Post to Pinterest (Using cURL)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $pinterest_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, ['message' => $message]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    echo "Pinterest post done!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

// Ab bas ye script GitHub pe save karke ek cron job ya manual run setup karna hai. 🚀
