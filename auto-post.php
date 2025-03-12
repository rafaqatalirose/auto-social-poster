<?php

$wordpress_api_url = 'https://newvideo.great-site.net/wp-json/wp/v2/posts';

// cURL request setup
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $wordpress_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // SSL verification disable kiya (sirf test ke liye)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "cURL error: " . curl_error($ch) . "\n";
} else {
    echo "HTTP Status Code: $http_code\n";
    if ($http_code === 200) {
        echo "WordPress API Response:\n";
        echo $response;
    } else {
        echo "Failed to fetch posts from WordPress API. HTTP Status Code: $http_code\n";
    }
}

curl_close($ch);
?>
