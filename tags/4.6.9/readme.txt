=== Shariff Wrapper ===
Contributors: starguide, 3UU
Tags: Shariff, GDPR, DSGVO, share buttons, sharing
Requires at least: 4.9
Requires PHP: 7.4
Tested up to: 6.1
Stable tag: 4.6.9
License: MIT
License URI: http://opensource.org/licenses/mit

Shariff provides share buttons that respect the privacy of your visitors and follow the General Data Protection Regulation (GDPR).

== Description ==

The "original" share buttons automatically transmit data of your visitors to the social network sites as soon as they visit your website. They do not need to click on a share button for this and therefore have no choice, if they want their data to be send. The German computer magazine c't has developed "Shariff" `(ʃɛɹɪf)` that follows the General Data Protection Regulation (GDPR - Regulation (EU) 2016/679). This plugin adapts the Shariff concept and provides an easy to use solution for WordPress. We currently support 30 services in 25 languages: AddThis, Bitcoin, Buffer, Diaspora, Facebook, Flipboard, LinkedIn, mailto, Mastodon, MeWe, Odnoklassniki, Patreon, PayPal, PayPal.me, Pinterest, Pocket, Printer, Reddit, RSS, SMS, Stumbleupon, Telegram, Threema, Tumblr, Twitter, VK, Wallabag, Weibo, WhatsApp, Xing.

For more information about the Shariff project check out the original [GitHub project](https://github.com/heiseonline/shariff) and read about the project itself [c’t information page](http://ct.de/shariff) (in German).

You can automatically add share buttons to posts, pages, the main blog page, product sites and many more as well as use it as a widget or add the shortcode `[shariff]` manually to your pages or themes.

== Installation ==

1. Upload everything to the `/wp-content/plugins/` directory
2. Activate the plugin using the plugins menu in WordPress
3. Use <code>[shariff]</code> anywhere on your site and/or use the Shariff settings menu.

To enable it for all posts please check the options in the plugin settings.

== Screenshots ==

1. Differently styled share buttons.
2. Basic options.
3. Design options.
4. Advanced options.
5. Statistic options.

== Frequently Asked Questions ==

= Can I use the Shariff buttons in my theme? =
Yes. Simply use the shortcode function:
<code>do_shortcode('[shariff]')</code>
You can use all options of the shorttag as described on the help tab in the plugin settings.

= Can I use the total amount of shares in my theme? =
Yes. You can use <code>do_shortcode('[shariff services="totalnumber"]')</code> to simply output the total amount of shares for a post in the loop. It will return the number itself wrapped in a <code><span class="shariff-totalnumber"></span></code> in order for the shariff.js to update the count. Also only cached data is used, in order to not slow down your site.

= Is there an action hook to use the share counts every time they get updated? =
Yes. You can use
<code>function your_awesome_function( $share_counts ) {
   // $share_counts is an array including all enabled services, 
   // the timestamp of the update and the url of the post.
   // do stuff
} 
add_action( 'shariff_share_counts', 'your_awesome_function' );</code>
WARNING: This hook will get called A LOT. So be sure you know what you are doing.

= How can I configure the widget? =
It uses the same options that have been configured on the plugin options page. However, you can put in a shorttag that overwrites the default options. It has the same format as you use in posts. Take a look at the help section of the plugin options page for more information.

= Can I change the options on a single post? =
A: Yes. You can change all options using the shorttag in the Shariff meta box on the right side of the post edit screen.

= Why are shares not listed? =
A: Shariff tries to protect the privacy of your visitors. In order to do this, the statistics have to be requested by your server, so social networks only see a request of your server and not from your visitor. However, we do not know, if you want this. Therefore it is not enabled by default.

= How can I show the share counts? =
Enable it on the plugin options page in general or add <code>backend="on"</code> to the shariff shorttag in your post.

= I still do not see share counts =
Please have a look at the status tab on the plugin options page. It states whether share counts are enabled and if there is a problem with a service. Please also keep in mind that the plugin has a minimum refresh time of 60 seconds and that each service has their own cache as well.

= Why can't I change the TTL to a smaller / bigger value? =
The time to live (TTL) value determines, if a share count of a post or page gets refreshed when someone visits this specific page / post of your blog. Too small values create too much useless traffic, too high values negate the goal of motivating visitors to also share a post. The value can be adjusted between 60 and 7200 seconds. Keep in mind, the actual lifespan depends on the age of the post as well.

= How can I change the position of all buttons? =
Have a look at the alignment options in the admin menu or checkout the style option.

= How can I change the design? =
Have a look at the parameters "theme", "orientation" and "buttonsize". They work mostly like the original code parameters that are explained at http://heiseonline.github.io/shariff/ Or you can have a look at the test page at https://shariff.3uu.net/shariff-sample-page-with-all-options to get an overview. But please be warned: This is a test page! It is possible that you find features that are only provided in the development version. Use it only to get an impression of the design options.

= How can I change the design of a single button? =
A: If you are a CSS guru please feel free to modify the css file. But of course this is a bad idea, because all changes will be destroyed with the next update! Instead take a look at the style and class attribute of the shorttag. If you put in any value it will create a DIV container with the ID "ShariffSC" around the buttons. If you are really a CSS guru you will know what does the magic from here on out. ;-)

= I want the buttons to stay fixed while scrolling! =
No problem. Just use the style attribute to add some CSS to the shorttag. For example in a widget (adjust the width as needed):
<code>[shariff style="position:fixed;width:250px"]</code>
Of course you can use all other options in that shorttag as well. It also works with the CSS style option on the plugins design options page, if you really want this applied to all buttons on your page.

= I want a horizontal line above my Shariff buttons! =
You can use the headline option on the design tab. For example, enter the following code to create a horizontal line and a headline:
<code><hr style='margin:20px 0'><p>Please share this post:</p></code>

= I want a different or no headline in a single widget, post or page! =
Use the headline attribute to add or remove it. For example, you can use the following shorttag to remove a headline set on the plugins options page in a single widget:
<code>[shariff headline=""]</code>
Of course you can use all other options in that shorttag as well.

= Can I add <code>[shariff]</code> on all posts? =
Yes, check out the plugin options.

= QBut I want to hide it on a single post! =
Do you really know what you want? ;-) However, it is possible. Write anywhere in your post "hideshariff". It will be removed and Shariff will not be added. You can also use "/hideshariff" to write "hideshariff" in your post. You might also want to take a look at the Shariff meta box on the right side of your post edit screen.

= Does it work with a CDN? =
Yes.

= Pinterest does not show an image! =
You can add <code>media="http://wwww.example.com/yourImage.png"</code> within the <code>[shariff]</code> shorttag or add it in on the plugin options page - of course with the link to your image.

= Can I set a fixed URL to share? =
You can use the "url" parameter within the shortcode
<code>[shariff url="http://www.example.com/"]</code>
This is also available within widgets. However, it is not a good idea to manipulate the URI, because it could mislead your visitors. So you should only use it, if this is really needed and you do really know what you are doing. Therefore it is not available on the plugin options page in general. 

= What happened to the Twitter share counts? =
Twitter does not offer official share counts anymore. As an alternative, share counts for Twitter can be requested via twitcount.com. You will need to register with them for it to work. Otherwise the count will always be zero.

= The buttons are not correctly being shown on my custom theme! =
Please make sure that <code>wp_footer();</code> has been added to your theme. For more information please visit: https://codex.wordpress.org/Function_Reference/wp_footer

= What is the external API feature? =
First of all: Usually you do not need it! The plugin requests all share counts itself. However, there are some reasons to put the backend on another server:
- avoid requests from you WP server to all the social networks
- use a more powerful server for the statistic
- use the original backend implementation of Heise or your own solution
- make your own backend available for more than one WP installation
But please have in mind that there are also some good reasons not to use external servers:
- you need an additional installation of WP and the plugin or have to create your own implementation of a Shariff backend
- some plugin settings (backend checks, statistic, etc.) will only work on the external server
- you have to use SHARIFF_FRONTENDS as an array with all your frontend domains to enable the backend or find your own solution
- we CANNOT provide support for your own implementation

= How can I configure the external API? =
In the statistic settings fill in the URL to the API of the external server. For the WordPress installation on the external server you have to create a "constant" called SHARIFF_FRONTENDS to permit other domains to use it. Please have in mind that you have to fill in all subdomains you want to use! The domains must be defined like this:
<code>define( 'SHARIFF_FRONTENDS', 'example.com|www.example.com|blog.example.com|another-domain.com' );</code>

= What does "Request external API directly." mean? =
By default, the browser request the share counts from the server your site is running on. If you have entered an external API your server will then request the counts from this external API instead of fetching them itself. Therefore, the external server will only see the IP from your server and not the one from your visitors. If you check this option, the browser of your visitors will instead directly request the share counts from the external API and therefore reveal their IP address to them. This might be faster, but it is less secure. Please also make sure to set the Access-Control-Allow-Origin header right. If your site is available using https, your external API will need to be reached by https as well. Otherwise the request will get blocked for security reasons. All options and features (e.g. the ranking tab) regarding the statistic will only work on the external server.

= KNOWN BUGS =

These are bugs or unexpected glitches that we know of, but that do not have an impact on the majority of users, are not security relevant and will perhaps be fixed in the future - if we have time to spend or you provide us with a lot of "K&#xF6;lsch" ;-)

- If the first post on the start page is password protected and Shariff is disabled on protected posts, a widget at the end of the loop will not be rendered.

== Changelog ==

= 4.6.9 =
- new service Mastodon (thanks to Nikita @kytta@fosstodon.org by providing s2f.kytta.dev)
- updated LinkedIn share link

= 4.6.8 =
- updated to Facebook Graph API v15.0
- removed deprecated services Flattr, QZone and TencientWeibo
- removed Twitter share counts via Twitcount
- removed broken links from help tab

= 4.6.7 =
- updated to Facebook Graph API v11.0
- changed FB request to og_object (thanks to richard67)

= 4.6.6 =
- Fix sanitize_callback parameter to avoid warnings with api requests
- tested for 5.7

= 4.6.5 =
- updated to Facebook Graph API v6.0
- fixed minor bug
- tested for WordPress 5.4

= 4.6.4 =
- fixed the settings link on the plugin overview page for certain environments (thanks to @midgard)
- updated to Facebook Graph API v5.0
- updated the WhatsApp link to swap url and title for a working image preview
- removed nofollow from the info button
- removed Xing share counts due to Xing disabling the API

= 4.6.3 =
- updated the WhatsApp share link to work with all devices again (thanks to @hanshansenxxx)
- updated to Facebook Graph API v3.3
- updated the Flattr button to reflect the new Flattr (thanks to Chris, @camthor)
- fixed an update issue with WP CLI
- removed the Facebook share counts request without APP ID and Secret
- Facebook now always requires an APP ID and Secret for share counts
- removed Flattr counts due to Flattr removing the API

= 4.6.2 =
- new service MeWe
- new service Buffer
- fixed an issue in case the plugin dir has been moved via symlink

= 4.6.1 =
- replaced Stumbleupon with its successor Mix (thanks to Mark)
- added an option to hide WhatsApp on desktop devices
- updated Odnoklassniki API
- fixed an issue with WPML and some older Shariff setups

= 4.6.0 =
- new high contrast theme (WCAG)
- improved support for WPML for easier translation of headlines and info button texts
- updated WhatsApp share link to support WhatsApp Web (thanks to Oliver, @oliverpw)
- updated Spanish translations (thanks to Torsten, @torstenbulk)
- updated Pinterest Share Count API
- updated VK Share Count API
- updated Pocket API
- updated XING API
- removed GooglePlus due to Google shutting GooglePlus down
- fixed a PHP notice in regards to Tumblr (thanks to Mario, @mariobartlack)
- fixed a conflict with another plugin (thanks to David, @daveshine)
- tested with WordPress 5.1

= 4.5.3 =
- removed LinkedIn Share Counts due to LinkedIn removing them completely
- removed GooglePlus Share Counts due to Google shutting GooglePlus down
- removed OpenShareCount due to the service having shut down
- removed NewShareCount due to the service having shut down
- removed Mastodon temporarily until a new working solution is available
- deprecated GooglePlus as a service, will be removed with the next release
- added TwitCount (twitcount.com) as an alternative for Twitter share counts
- updated to Facebook Graph API v3.2
- updated to WordPress Coding Standards 2.0
- corrected minor typos
- added the new logo thanks to Philipp Wildfeuer (@phil_sauvage)

= 4.5.2 =
- added support for share count requests of multilingual sites
- updated button translations for Twitter and Pinterest (thanks to Jessica, @jess78)
- updated to Facebook Graph API v3.0

= 4.5.1 =
- added support for the new WordPress Privacy Policy Guide added in 4.9.6
- minor css adjustments
- minor bug fixes

= 4.5.0 =
- new option to add Shariff to custom WordPress hooks
- new option to support multilingual sites using WPML and other plugins
- new support for WooCommerce products on the ranking table
- new option to show different headlines based on share counts
- updated button languages, now supporting 25 languages
- fixed a bug causing share counts to not being displayed properly

The complete changelog can be found here: https://plugins.svn.wordpress.org/shariff/trunk/changelog.txt
