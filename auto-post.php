<?php

// === Configurations === //

// WordPress REST API ka URL (aapka site URL)
$wp_api_url = 'https://newvideo.great-site.net/wp-json/wp/v2/posts';

// Pinterest board ka naam (manual link ke liye)
$pinterest_board = 'https://www.pinterest.com/aa4783116/movie-trailers-and-clips';

// Kitne posts fetch karne hain?
$post_limit = 5;

// cURL se WordPress API se posts fetch karte hain
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "$wp_api_url?per_page=$post_limit");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200 && $response) {
    $posts = json_decode($response, true);
    
    if (!empty($posts)) {
        foreach ($posts as $post) {
            $title = $post['title']['rendered'];
            $link = $post['link'];
            $image_url = $post['yoast_head_json']['og_image'][0]['url'] ?? '';
            
            // Pinterest ka manual Pin link generate karte hain
            $pinterest_link = "https://www.pinterest.com/pin-builder/?
                url=" . urlencode($link) .
                "&media=" . urlencode($image_url) .
                "&description=" . urlencode($title) .
                "&board=$pinterest_board";

            echo "Post Title: $title\n";
            echo "Post Link: $link\n";
            echo "Pinterest Pin Link: $pinterest_link\n";
            echo "--------------------------\n";
        }
    } else {
        echo "No posts found from WordPress API.\n";
    }
} else {
    echo "Failed to fetch posts from WordPress API. HTTP Status Code: $http_code\n";
}

?>

// === Usage Instructions === //
// 1. Script ko `script.php` ke naam se save karein.
// 2. Terminal ya GitHub Action me run karein: `php script.php`
// 3. Har post ke liye Pinterest ka link milega — bas browser me open karein aur Pin kar dain! 🎯
