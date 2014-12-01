=== Shariff ===
Contributors: 3UU
Tags: Twitter, Facebook, GooglePlus, sharebutton
Requires at least: 3.0.1
Tested up to: 4.0.1
Stable tag: trunk
License: MIT
License URI: http://opensource.org/licenses/MIT

A better way to use share buttons of Twitter, Facebook and GooglePlus. This 
is a wrapper for the original code by the "Shariff" project.

== Description ==

The "original" share buttons send data of your visitors to the social
network site also if they do not really click on the share button. The
german computer magazin CT has developed "Shariff" that fullfill the 
strict data protection law in Germany. This plugin is a wrapper to this 
software. It allows you to use it within your posts with the shorttag 
[shariff] and provide all design options of the original code.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use <code>[shariff]</code> anywhere in your posts.

= Supported options are: =

Because of WP coding the attibutes have not the "data-" prefix of the
original Shariff code!

* services=twitter|facebook|googleplus
* info-url= http://ct.de/-2467514 (default)
* lang= de|en
* theme= default|grey|white
* orientation= vertical
* backend= on|off(default)

== Screenshots ==

1. `/assets/screenshot-1.jpg`
2. `/assets/screenshot-2.jpg`

== Frequently Asked Questions ==

= Q: Why are shares/likes not listed? =
A: Shariff try to protect your visitors. Therefor the statistic must
requested by your server. So social networks see only a request of your blog
server. However I am not sure if do you want this. So it is not enabled by
default. 

= Q: How can I show the share counts? =
A: Add the option "backend=on" to the shariff shorttag in your post.

= Q: I need some more options. How could I get them? =
A: This plugin is a wrapper to the original project files of Shariff. As
long as the original will not get more options we will not add more to this 
plugin.

= Q: How can I change the design? =
A: Have a look at the parameters "theme" and "orientation". They work
like the original code parameters that are explained at 
http://heiseonline.github.io/shariff/

= Q: Can I add [shariff] on all posts? =
A: Yes, if do you have access to your wp-config.php file. Add
define('SHARIFF_ALL_POSTS','[shariff]');
to it. The shortcut will be put at the end of all single posts and processed.
You can use all options that work with the shortcut.

= Q: But I want hide it on a single post! =
A: Do you really know what do you want? ;-) However it is possible. Write 
anywhere in your post "hideshariff". It will be removed and Shariff will 
not be added. This will make it compatible with the other plugin "Shariff for
Wordpress" that has been published at the same day. You can also use
"/hideshariff" to write "hideshariff" in your post. 

= Q: What are the differences between this 2 plugins? =
A: One is developed by me, one by an other ;-) The main difference is, that 
this plugin can be used as a shortcode anywhere in your posts. Also if do 
you use the configuration option to add it on all posts - it will only 
shown on single post pages.

= Q: The fonts load slowly, can I use a CDN? =
A: The best way would be to use a CDN for your blog host. The original 
shariff project has references to CDN hosted fonts. But it is possible that 
the hoster send headers that force the browser to reload the fonts on any
page. This will enable tracking again depending on how honest the font
hoster is. So I would suggest to use the copy that is within the plugin 
directory at your server. However if do you want use the orginal CSS of the
Shariff project you can rename the shariff.min.css to
shariff.min.local.css Please be warned: To use a plugin to help your
visitors not to compromising their privacy and than make use of external
hosted parts is not really smart.

== Changelog ==

= 0.3 =
* add support for "hideshariff" 
* add screenshots

= 0.2 =
* removed the private update server and changed test domain 

= 0.1 = 
* initial

== Upgrade Notice ==

= 0.1 = 
Initial code. See README
