// RSS Feed URL
$rss_feed_url = 'https://newvideo.great-site.net/feed/';

// Fetch RSS Feed using cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $rss_feed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Pretend to be a real browser
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/rss+xml, application/xml, text/xml',
    'Cache-Control: no-cache',
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/98.0.4758.102 Safari/537.36'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Debug Response
if ($http_code === 200 && $response) {
    echo "Response from RSS Feed:\n";
    echo $response;
} else {
    echo "Failed to fetch RSS feed. HTTP Status Code: $http_code\n";
}
// Debug Response
if ($http_code === 200 && $response) {
    echo "Response from RSS Feed:\n";
    echo $response;
} else {
    echo "Failed to fetch RSS feed. HTTP Status Code: $http_code\n";
    echo "Raw response (if any):\n";
    echo $response ? $response : "No response received.\n";
}
