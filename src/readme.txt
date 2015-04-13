=== Shariff for Wordpress ===
Contributors: yanniks
Tags: sharing, social, networks, network, privacy, facebook, twitter, google, whatsapp, linkedin, heise, pinterest
Requires at least: 3.0
Tested up to: 4.1
Stable tag: 1.1.0
License: MIT
License URI: http://opensource.org/licenses/MIT

Shariff enables website users to share their favorite content without compromising their privacy.

== Description ==

This is the Shariff for WordPress plugin based on c't Shariff.

For more information, check out the original [GitHub project](https://github.com/heiseonline/shariff) and the [c’t information page](http://ct.de/shariff).

> Shariff enables website users to share their favorite content without compromising their privacy.

> Facebook, Google+ and Twitter supply official sharing code snippets which quietly siphon personal data from all page visitors. Shariff enables visitors to see how popular your page is on Facebook and share your content with others without needless data leaks.

> Shariff `(/ˈʃɛɹɪf/)` is an open-source, low-maintenance, high-privacy solution maintained by German computer magazine c't and heise online.

Shariff supports sharing buttons for Facebook, Twitter, Google+, LinkedIn, Pinterest, Reddit, StumbleUpon, XING, WhatsApp and mail.
Select which color you want, set the button location, select the orientation fitting most to your website and set the TTL just as you want.

== Installation ==

1. Upload the `shariff-wp` directory to `/wp-content/plugins/`
2. Activate the plugin through th ‚Plugins‘ menu in WordPress
3. Modify the settings as you want through Settings -> Shariff

== Screenshots ==

1. `/assets/screenshot-1.png`
2. `/assets/screenshot-2.png`
3. `/assets/screenshot-3.png`

== Frequently Asked Questions ==

= How do I hide Shariff on a page? =

**Method A:** Edit the article and check `Deactivate Shariff?`.

**Method B:** Include `hideshariff` in the article. The word will be removed automatically and Shariff will not be shown. If you want to write hidesharrif in an article without removing Shariff, just write `/hideshariff`.

= Shariff doesn't show any numbers =
Your user probably doesn't have the right to write to the default temp folder `/tmp`. Then create a new folder and use it as temp folder in the Shariff settings, make sure that the rights are set correct.
== Changelog ==

= 1.1 =
* Round theme and french support frontend translation (thanks to 3UU)
* Insert Shariff by using the `shariffhere` tag
* Support for Reddit and StumbleUpon
* Counts for all supported services
* Bug fixes
* Upstream changes
* Some other stuff

= 1.0.11 =
* Spanish support
* WhatsApp only on mobile devices

= 1.0.10 =
* WhatsApp fixes

= 1.0.9 =
* bug fixes
* font awesome now part of the package
* PHP version check
* Option to hide Shariff on article overview pages

= 1.0.8 =
* security fix

= 1.0.7 =
* upstream changes
* possibility to manually change temp dir

= 1.0.6 =
* Bug fixes

= 1.0.5 =
* Add Pinterest and XING (without count)
* Fix mail

= 1.0.4 =
* Add experimental LinkedIn support
* Set default TTL to 60

= 1.0.3 =
* Include latest upstream changes
* Checkbox to hide Shariff when editing articles or pages

= 1.0.2 =
* Own settings page
* Possibility to hide Shariff on a certain page

= 1.0.1 =
* Fixes a PHP warning

= 1.0 =
* Initial release
