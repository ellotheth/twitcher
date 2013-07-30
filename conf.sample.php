<?php

/* Edit this file with your Twitch and Google information */

/* URL to fetch the JSON for user highlight videos */
$video_url = 'https://api.twitch.tv/kraken/channels/<twitch username>/videos';

/* Full gmail address */
$gmail_user = '<your-gmail-handle>@gmail.com';

/* Application specific password if you have two-factor authentication:
 * https://accounts.google.com/b/0/IssuedAuthSubTokens
 */
$gmail_pass = '<google password>';

/* Setup here: http://bamajr.com/2013/02/16/posting-to-google-plus-via-email/ */
$gplus_email = '<gvoice #>.33669.<unique key>@txt.voice.google.com';

/* Leave empty to share with 'Your Circles', or add each circle individually */
$included_circles = array();

/* Shows up in the 'Received' email header */
$localhost = '<your server>';

/* Path to log file */
$log_file = '/path/to/log';
?>
