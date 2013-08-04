#!/usr/bin/php
<?php

/* needs PEAR packages Mail and Net_SMTP */
require_once 'Mail.php';

/* see conf.sample.php */
require_once 'conf.php';

/* https://github.com/justintv/Twitch-API/blob/master/v2_resources/videos.md */
define('TWITCH_URL', 'https://api.twitch.tv/kraken/channels/USERNAME/videos');

/* get the list of videos already broadcasted, or init the log */
if (!($posted = @file_get_contents($log_file))) {
    if (!file_put_contents($log_file, "Twitcher log:\n")) {
        error_log('Failed to init log');
        exit(1);
    }
}

/* cheating: don't need to get fancy with the api, just grab everything once */
$video_url = str_replace('/USERNAME/', "/$twitch_user/", TWITCH_URL);
if (!($json = @file_get_contents($video_url))) {
    error_log('Failed to get json');
    exit(1);
}
if (!($videos = json_decode($json))) {
    error_log('Failed to parse json');
    exit(1);
}

/* if $twitch_user has no video highlights, we're done */
if (!$videos->{'videos'}) {
    echo "No highlights to post\n";
    exit(0);
}

/* set up the mailer */
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
if ($smtp instanceof PEAR_Error) {
    error_log('Failed to create mailer: '.$smtp->getMessage());
    exit(1);
}

/* set up circles and tags */
$circles = ($included_circles) ? '+'.implode(' +', $included_circles).' ' : '';
$tags = ($included_tags) ? '#'.implode(' #', $included_tags).' ' : '';

/* compose a message for each video to be posted, skipping videos that were
 * already posted */
foreach ($videos->{'videos'} as $video) {
    if (preg_match('/\b'.$video->{'_id'}.'\b/', $posted)) continue;

    /* strip characters in the game name that won't work in a hashtag */
    if ($video->{'game'}) {
        $gametag = '#'.preg_replace('/[^\w]+/', '', $video->{'game'}).' ';
    } else $gametag = '';

    /* Format: title (URL) tags circles */
    $body = $video->{'title'}.' ('
           .$video->{'url'}.') '
           .$tags.$gametag
           .$circles;
    $posts[] = array('body' => $body, 'id' => $video->{'_id'});
}

/* if all the videos in the stream were broadcasted already, we're done */
if (!isset($posts)) exit(0);

/* post one video at a time, because otherwise google gets really confused */
$post = array_shift($posts);
$mail = $smtp->send($headers['To'], $headers, $post['body']);

if (PEAR::isError($mail)) {
    error_log($mail->getMessage());
    exit(1);
}
else echo "Sent '".$post['body']."'\n";

/* log the posted video so we don't post it again on the next run */
$log_entry = date(DATE_ATOM).': '.$post['id']."\n";
if (!file_put_contents($log_file, $log_entry, FILE_APPEND)) {
    error_log("Failed to log post $log_entry");
    exit(1);
}

/* notify if there are more videos to be posted */
if ($posts) echo count($posts)." video(s) still in the queue\n";

