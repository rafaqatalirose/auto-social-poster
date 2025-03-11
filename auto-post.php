$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "cURL error: " . curl_error($ch) . "\n";
} else {
    echo "HTTP Status Code: $http_code\n";
    if ($http_code === 200 && $response) {
        echo "RSS Feed Response (Raw):\n";
        echo $response . "\n"; // Raw response ko dekhne ke liye

        // XML parsing errors ko catch karne ke liye
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xml) {
            foreach ($xml->channel->item as $item) {
                echo "Title: " . html_entity_decode($item->title) . "\n";
                echo "Link: " . $item->link . "\n";
                echo "Publish Date: " . $item->pubDate . "\n";
                echo "--------------------------\n";
            }
        } else {
            echo "Failed to parse RSS feed. Errors:\n";
            foreach (libxml_get_errors() as $error) {
                echo "Error: " . $error->message . "\n";
            }
            libxml_clear_errors();
        }
    } else {
        echo "Failed to fetch RSS feed. HTTP Status Code: $http_code\n";
    }
}

curl_close($ch);
