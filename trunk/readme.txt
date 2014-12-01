=== Shariff ===
Contributors: 3UU
Tags: Twitter, Facebook, GooglePlus, sharebutton
Requires at least: 3.0.1
Tested up to: 4.0.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

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
3. Use <code>[shariff] anywhere in your posts.

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

1.) Add attributes to the shorttag 

2.) how does it look with the attributes or screenshot 1

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
A: Have a look at the prameters "theme" and "orientation". They work
like the original code parameters that are explained at 
http://heiseonline.github.io/shariff/

== Changelog ==

= 0.1 = 
* initial

== Upgrade Notice ==

= 0.1 = 
Initial code. See README
