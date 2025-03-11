<?php

// RSS Feed URL
$feed_url = 'https://newvideo.great-site.net/feed/';

// cURL se RSS fetch karna
$ch = curl_init($feed_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/110.0 Safari/537.36'
]);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "cURL error: " . curl_error($ch) . "\n";
} else {
    echo "HTTP Status Code: $http_code\n";

    if ($http_code === 200 && $response) {
        echo "RSS Feed Response (Raw):\n";
        
        // Encoding fix
        $decoded_response = mb_convert_encoding($response, 'UTF-8', 'auto');
        
        // XML parsing
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($decoded_response, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xml && isset($xml->channel->item)) {
            echo "\nPosts Found:\n";

            foreach ($xml->channel->item as $item) {
                $title = html_entity_decode($item->title);
                $link = $item->link;
                $pubDate = date('Y-m-d H:i:s', strtotime($item->pubDate));

                echo "--------------------------\n";
                echo "Title: $title\n";
                echo "Link: $link\n";
                echo "Publish Date: $pubDate\n";
                echo "--------------------------\n";

                // Dummy POST request (replace with real API)
                echo "Posting to site...\n";
                
                $post_data = [
                    'title' => $title,
                    'link' => $link,
                    'publish_date' => $pubDate
                ];

                // Example API call (replace with your endpoint)
                // curl_post('https://your-site.com/api/create-post', $post_data);
            }
        } else {
            echo "Failed to parse RSS feed.\n";
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

?>

<!--
 * Yeh script:
 * 1. RSS feed fetch karti hai.
 * 2. XML ko parse karti hai.
 * 3. Title, link, aur date ko print karti hai.
 * 4. Post data ko ready karti hai API ke liye.
 *
 * Aapko sirf real API endpoint lagana hai jahan post karwana hai!
 -->

<!-- Example API function (real site ke liye enable karein) -->
<?php
// function curl_post($url, $data) {
//     $ch = curl_init($url);
//     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//     curl_setopt($ch, CURLOPT_POST, true);
//     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
//     $response = curl_exec($ch);
//     curl_close($ch);
//     echo "API Response: $response\n";
// }
?>

<!-- Ab script ko chalayein: php auto-post.php -->

<!-- Fikar na karein, agar koi error aaya to hum mil ke fix karenge! 🚀 -->
