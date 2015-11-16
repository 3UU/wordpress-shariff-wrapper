This Wordpress plugin is a wrapper to Shariff. The intension is to use as
much as possible of the original Shariff code, but make some changes to
optimize it for Wordpress e.g. Fonts, JQuery, WP default theme CSS...

This is the development Repo for https://wordpress.org/plugins/shariff/

/shariff/	= WP plugin trunk (includes backend files, locale etc.)
/src/		= Source for JS and CSS
/build/		= dir for JS and CSS 
		  (That usually will be the same as in /shariff/, 
		   use grunt wp2, if you want to build it within
		   the plugin directory.)
/assets/	= files used on WP plugin page

#########################################################################

git clone https://github.com/3UU/wordpress-shariff-wrapper.git
npm install
npm install browserify-window --save
# if grunt does not work 
# npm install grunt-cli -g
grunt wp
