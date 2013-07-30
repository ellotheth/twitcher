#!/usr/bin/php
<?php

require_once 'Mail.php';
require_once 'conf.php';

if (!($posted = @file_get_contents($log_file))) {
    if (!file_put_contents($log_file, "Twitcher log:\n")) {
        error_log('Failed to init log');
        exit(1);
    }
}

if (!($json = @file_get_contents($video_url))) {
    error_log('failed to get json');
    exit(1);
}

if (!($videos = json_decode($json))) {
    error_log('failed to parse json');
    exit(1);
}

if (!$videos->{'videos'}) {
    echo "No highlights to post\n";
    exit(0);
}

foreach ($videos->{'videos'} as $video) {

    $tags = '#twitch #'.preg_replace('/\s+/', '', $video->{'game'});

    $body = $video->{'title'}.' ('
           .$video->{'url'}.') '
           .$tags;
    $posts[] = array('body' => $body, 'id' => $video->{'_id'});
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

while ($post = array_shift($posts)) {
    if (preg_match('/\b'.$post['id'].'\b/', $posted)) continue;
    break;
}

if ($post) {
    $body = $post['body'].$circles;
    $mail = $smtp->send($headers['To'], $headers, $body);

    if (PEAR::isError($mail)) error_log($mail->getMessage());
    else echo "Sent '$body'\n";

    $log_entry = date(DATE_ATOM).': '.$post['id']."\n";
    if (!file_put_contents($log_file, $log_entry, FILE_APPEND)) {
        error_log("Failed to log post $log_entry");
        exit(1);
    }
}

if ($posts) echo count($posts)." video(s) still in the queue\n";

