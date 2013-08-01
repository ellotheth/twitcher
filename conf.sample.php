<?php

/* Edit this file with your Twitch and Google information, and save it as
 * conf.php
 */

/* Twitch.tv username to search for new videos */
$twitch_user = '<twitch username>'

/* Full gmail address */
$gmail_user = '<your-gmail-handle>@gmail.com';

/* Application-specific password if you have two-factor authentication:
 * https://accounts.google.com/b/0/IssuedAuthSubTokens
 */
$gmail_pass = '<google password>';

/* Setup: http://bamajr.com/2013/02/16/posting-to-google-plus-via-email/ */
$gplus_email = '<gvoice #>.33669.<unique key>@txt.voice.google.com';

/* Circles to include in the broadcast
 *
 * Your circles (default): $included_circles = array();
 * Public:                 $included_circles = array('Public');
 * Gamers and Friends:     $included_circles = array('Gamers', 'Friends');
 * etc.
 */
$included_circles = array();

/* Shows up in the 'Received' email header instead of 'localhost' */
$localhost = '<your server>';

/* Path to posting log */
$log_file = '/path/to/log';
