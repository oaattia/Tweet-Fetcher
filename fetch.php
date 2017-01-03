<?php

require_once('vendor/autoload.php');

if ( ! isset($_POST['user']) && empty($_POST['user'])) {
    return;
}

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();

$settings = [
    'oauth_access_token'        => getenv('OAUTH_ACCESS_TOKEN'),
    'oauth_access_token_secret' => getenv('OAUTH_ACCESS_TOEKN_SECRET'),
    'consumer_key'              => getenv('CONSUMER_KEY'),
    'consumer_secret'           => getenv('CONSUMER_SECRET')
];

$limit    = 40;
$count    = 20;
$max_id   = '';
$old_id   = null;
$getfield = [];

try {

    while ($count <= $limit) {

        //$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $url = 'https://api.twitter.com/1.1/search/tweets.json';


        if ( ! empty($max_id)) {
            $getfield = [
                'max_id' => $max_id
            ];
        }

        $getfield = $getfield + [
                'q'     => 'to:' . $_POST['user'],
//                'until' => '2015-12-12'           // will not work https://twittercommunity.com/t/how-to-get-tweets-for-specific-date-duration/14775/4
            ];

        $twitter = new TwitterAPIExchange($settings);
        $content = $twitter->setGetfield('?' . urldecode(http_build_query($getfield)))
                           ->buildOauth($url, 'GET')
                           ->performRequest(true, [CURLOPT_SSL_VERIFYPEER => false]);

        $content = json_decode($content);

        if (isset($content->errors) && $content->errors) {
            http_response_code(500);
            echo json_encode(['message' => $content->errors[0]->message, 'code' => 500]);

            return;
        }

        if (empty($content->statuses)) {
            echo json_encode(['message' => 'no statuses found', 'code' => 500]);

            return;
        }

        $contents[] = $content;
        $max_id     = $content->statuses[count($content->statuses) - 1]->id_str;
        $count      = $count + 20;
    }


    echo json_encode(['data' => $contents]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => $e->getMessage(), 'code' => 500]);
    exit;
}
