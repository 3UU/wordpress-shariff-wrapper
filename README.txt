This Wordpress plugin is a wrapper to Shariff. The intension is to use as
much as possible of the original Shariff code but make some changes to
optimize it for Wordpress i.e. Fonts, JQuery, WP default theme CSS...

This is the development Repo for https://wordpress.org/plugins/shariff/

/shariff/	= WP plugin trunk (include backend files, locale etc.)
/src/		= Source for JS and CSS
/build/		= dir for JS and CSS 
		  (That usually will be the same as in /shariff/ . 
		   Change your Grundfile if do you want direct within
		   the plugin directory.)
/assets/	= files used on WP plugin page

#########################################################################

git clone https://github.com/3UU/wordpress-shariff-wrapper.git
npm install
npm install browserify-window --save
grunt wp
