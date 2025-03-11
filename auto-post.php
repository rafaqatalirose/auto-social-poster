$rss_feed_url = 'https://newvideo.great-site.net/feed/';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $rss_feed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "cURL error: " . curl_error($ch) . "\n";
} else {
    echo "HTTP Status Code: $http_code\n";
    if ($http_code === 200 && $response) {
        echo "RSS Feed Response:\n";
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml) {
            foreach ($xml->channel->item as $item) {
                echo "Title: " . htmlspecialchars($item->title) . "\n";
                echo "Link: " . $item->link . "\n";
                echo "Publish Date: " . $item->pubDate . "\n";
                echo "--------------------------\n";
            }
        } else {
            echo "Failed to parse RSS feed.\n";
        }
    } else {
        echo "Failed to fetch RSS feed. HTTP Status Code: $http_code\n";
    }
}

curl_close($ch);
