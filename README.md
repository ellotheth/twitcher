# Twitcher

*Shell script to post new video highlights from a Twitch.tv account to Google Plus*

I'm one of the nine people still using G+, and I'm unwilling to hand Twitch my Twitter keys, **and** my streaming engine doesn't play nice with Twitch's YouTube importer, so here we are. Twitch.tv → Google+, no stops in between.

The post itself ends up looking like this:

    [video title] ([video URL]) #twitch #[game title] +[circles]

## Requirements

- PHP 5.4
- PEAR/Net_SMTP
- PEAR/Mail
- [Your Google Voice SMS email address][1]

## Install

1. Clone the repo
2. Copy `conf.sample.php` to `conf.php` and update it with your own settings (see the comments for details)
3. Make sure twitcher.php is executable
4. Run twitcher.php directly, or throw it in a cron job

## Caveats

- Google has not seen fit to hand developers a posting API, so this script hacks its way through the SMS G+ interface with a Google Voice pseudo-text-message. Google Voice will get (for lack of a better term) clogged if you send it several updates simultaneously, which is why the script sends one at a time and saves the rest. Trust me, don't just run this in a loop. I had G+ posts showing up six hours after I'd triggered them, it was absurd.
- The mechanism that grabs all your video highlights is fairly stupid, so don't lose your posting log or you'll get duplicate posts. If you do lose your log, you can recreate it as a text file, then add all your Twitch video IDs (separated by spaces, commas, new-lines, etc).
- I haven't tried it on Windows. Might work fine!

## Questions/comments/contributions

Shoot me an email, write up an issue, send a pull request.

### Why is this in PHP instead of Ruby/Python/literally anything else?

Because go away, that's why.

[1]: http://bamajr.com/2013/02/16/posting-to-google-plus-via-email/