=== Shariff ===
Contributors: 3UU
Tags: Twitter, Facebook, GooglePlus, sharebutton, sharing, privacy, social, whatsapp
Requires at least: 3.0.1
Tested up to: 4.1
Stable tag: trunk
License: MIT
License URI: http://opensource.org/licenses/MIT
Donate link: http://folge.link/?bitcoin=1Ritz1iUaLaxuYcXhUCoFhkVRH6GWiMTP
Author: Ritze

This is a wrapper to Shariff. Enables shares with Twitter, Facebook... on posts, pages and/or themes with no harm for visitors privacy.

== Description ==

The "original" share buttons send data of your visitors to the social
network site also if they do not really click on the share button. The
german computer magazin CT has developed "Shariff" that fullfill the strict 
data protection law in Germany. This plugin is a wrapper to this software. 
It allows you to use it within your posts with the shorttag [shariff] and 
provide all design options of the original code.

For more informations about the Shariff software check out the 
[GitHub project](https://github.com/heiseonline/shariff) and
about the project read [c’t information page](http://ct.de/shariff).

You can use this plugin as widget, for single or all posts and as well on
pages. It also support the hideshariff sign that used by Yanniks plugin.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Use <code>[shariff]</code> anywhere in your post or/and use the admin menu. 

To enable it for all posts plz check the option in the admin menu.

== Screenshots ==

1. Insert as shorttag with options in a post
2. Share-Buttons on the site in vertical order
3. Options menu
4. Widget menu. Shorttag work like in posts
5. also any widget can have own shorttag design

== Frequently Asked Questions ==

= Q: Can I use it in my theme? =
A: Yes. `<?=do_shortcode('[shariff backend="on"]')?>` Backend is an example.
You can use all options of the shorttag. But first you should have a look at
the widget. 

= Q: How can I configure the widget? =
A: It use the same options that have been configured in the admin menu.
However you can put in a shorttag that overwrites the default options. It
has the same format as do you use in post.

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
http://heiseonline.github.io/shariff/ Or you can have a look at my test page
at http://shariff.3uu.net/shariff-sample-page-with-all-options to get an
overview. But plz be warned: This is my test page! It is possible that do
you find features that are only provided in the development version. Use it
only to get an impression of the design options.

= Q: Can I add [shariff] on all posts? =
A: Yes, use the admin menu and use the first checkbox to enable it. If do you 
have access to your wp-config.php file there is also an other way. Add
define('SHARIFF_ALL_POSTS','[shariff]');
to it. The shortcut will be put at the end of all single posts and processed.
You can use all options that work with the shortcut.

= Q: Can I change the design on a single post? =
A: Yes. If the [shariff... shortcut is found on a post the SHARIFF_ALL_POSTS
will be disabled (only) on this site. So you can make the special design.

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
shown on single post pages. And this plugin also provide a widget.

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

= Q: I do not get the numbers / Got error "tmp dir must be writable" =
A: Your webserver must be able to connect to other webservers. Please check
your PHP configuration and firewall settings. If do you get the error "tmp dir
must be writable" your webserver does not have a directory to write
temporary data. Either `/tmp` or the `upload_tmp_dir` (usually configured in 
your php.ini) must be writable. Also please have in mind that the plugin has
a timeout of 60 seconds because most services do not accept more request.

= Q: Pinterest does not show an icon =
A: You can add 
media="http://wwww.example.com/yourImage.png"
within the [shariff...] short tag. Of course with the link to your image ;-)

== Changelog ==
= 1.2.7 =
- fixed: enable WhatsUp on mobile now also works with Mozilla 
- print button does not open a new window
- removed min.js make it more readable for own local changes

= 1.2.6 =
- add print button
- add default image for pinterest to avoid broken design and giuve a hint

= 1.2.5 =
- hotfix for pinterest (see FAQ) 

= 1.2.4 =
- bugfix: widget does not work if SHARIFF_ALL_POSTS is set but not enabled
  in the admin menu (Please remember, that SHARIFF_ALL_POSTS will be 
  removed with next major version)
- add option to add shariff at the begin of a post
- merge with new original backend for counters
- add spanish language on buttons; hide whatsup on mobile devices
  (merge with yanniks code)
- add reddit
- add stumbleupon

= 1.2.3 =
* add round theme to the admin menu

= 1.2.2 =
* tested with WP 4.1
* added french language for buttons
* added theme "round" (round buttons without text but with fixed width)

= 1.2.1 =
* typos

= 1.2 =
* add widget support

= create a Stable1.0 tag =
* no new funtionality to this tag. Only bugfixes!

= 1.1.1 =
* add french language for the admin menu (thanks Celine ;-)
* fix backend problem on shared hosting with no writeable tmp dir

= 1.1 =
* add whatsapp|pinterest|linkedin|xing
* include latest upstream changes (fix mail etc.)
* add old default selection of services to make it backward compatible

= 1.0.2 =
* add German translation to the admin menu (Admin-Menue in Deutsch)
* code cleanup

= 1.0.1 =
* add PHP version check (5.4 is needed by the backend option)

= 1.0 =
* add admin menu page
* disable the default add on a post if a special formed tag was found
* add support for the theme attribute

= 0.4 =
* Include latest upstream changes
* use get_permalink() to set the parameter data-url

= 0.3 =
* add support for "hideshariff" 
* add screenshots

= 0.2 =
* removed the private update server and changed test domain 

= 0.1 = 
* initial

== Upgrade Notice ==

= 1.0 =
Added admin menu. Support for SHARIFF_ALL_POSTS constant can be  
removed with next major release! 

= 0.1 = 
Initial code. See README
