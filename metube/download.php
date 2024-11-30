<?php
set_time_limit(0);
require './YoutubeDownloader.php';

if (!isset($_GET['id'])) {
    header('Bad request', true, 400);
    echo "id param is required";
    die();
}
$id = $_GET['id'];

$yd = new YoutubeDownloader('https://www.youtube.com/watch?v=' . $id);

if (!isset($_GET['itag'])) {
    $info = $yd->getFullInfo();
    $itag = $info['url_encoded_fmt_stream_map'][0]['itag'];
} else {
    $itag = $_GET['itag'];
}

$yd->downloadForItag($itag);
