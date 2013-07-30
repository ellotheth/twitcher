#!/usr/bin/php
<?php

require_once 'Mail.php';
require_once 'conf.php';

$json = file_get_contents($video_url);

if (!$json) {
    error_log('failed to get json');
    exit(1);
}

if (!($videos = json_decode($json))) {
    error_log('failed to parse json');
    exit(1);
}

foreach ($videos->{'videos'} as $video) {

    $tag = '#twitch #'.preg_replace('/\s+/', '', $video->{'game'});

    $posts[] = $video->{'title'}.' ('
              .$video->{'url'}.') '
              .$tag;
}

$circles = '';
foreach ($included_circles as $circle) $circles .= " +$circle";

$headers = array(
    'From' => $gmail_user,
    'To' => $gplus_email,
    'MIME-Version' => '1.0',
    'Reply-To' => $gmail_user,
);
$smtp = Mail::factory('smtp', array(
    'localhost' => $localhost,
    'host' => 'ssl://smtp.gmail.com',
    'port' => '465',
    'auth' => true,
    'username' => $gmail_user,
    'password' => $gmail_pass,
    'persist' => true,
));

foreach ($posts as $post) {
    $body = $post.$circles;

    $mail = $smtp->send($headers['To'], $headers, $body);
    if (PEAR::isError($mail)) echo $mail->getMessage()."\n";
    else echo "Sent '$body'\n";

    if ($post !== end($posts)) sleep(300);
}
