// RSS Feed URL
$rss_feed_url = 'https://newvideo.great-site.net/feed/';

// Fetch RSS Feed using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $rss_feed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
$response = curl_exec($ch);
curl_close($ch);

// Debug Response
if ($response) {
    echo "Response from RSS Feed:\n";
    echo $response;
} else {
    echo "Failed to fetch RSS feed.";
}
