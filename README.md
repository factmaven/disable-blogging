# Disable Blogging 

[![plugin version](https://img.shields.io/wordpress/plugin/v/disable-blogging.svg)](https://wordpress.org/plugins/disable-blogging)

Disables posts, comments, feeds, and other related the blogging features in WordPress.

## Description 
**Disable Blogging** is a plugin that disables all blogging-related functionalities on WordPress on the front-end and the back-end. This makes for a cleaner and simpler WordPress platform to be used for static websites.

### Remove sidebar & toolbar menu items 
In the admin dashboard - `Posts`, `Comments`, and other blog related menu items are removed from sidebar and toolbar and redirected to `Pages` menu.

### Disable all posting & commenting functions 
Comments are disallowed and disabled on pages and all blog related widgets are removed.

### Remove all feed links from the header 
This includes pingbacks, trackbacks, XML-RPC, Windows Live Writer.

### Simplify user profile page 
Hide unused fields (such as "Biographical Info") and options (such as "Admin Color Scheme") from the `Profile` page to reduce clutter.

### Other additional features included 
* Disabling "Press This" function
* Disabling posting via email
* Removes "Howdy," from the toolbar
* Removes "Help" tabs in upper right in the dashboard
* Removes query strings (`ver=`) from static resources

### Notice 
This plugin does not delete any data on WordPress. It simply hides and disables those features that are blog related. If you have any existing, posts, comments, categories and tags on your website; they must be manually deleted if you do not want to keep any of that information.

## Frequently Asked Questions 

### Are these changes permanent? 
No, this plugin simply disables, hides, and redirects all of the blogging functions. You can easily revert back by simply disabling the plugin.

### So these blogging functions are just hidden from view? 
No, they are also disabled from being access as well for added security. If someone were to access the *Posts* menu:
`wordpress.org/wp-admin/post.php`
They would be redirected to the *Pages* menu:
`wordpress.org/wp-admin/edit.php?post_type=page`

### I can still access the XML-RPC when I visit `myweb.site/xmlrpc.php` even through it's removed form my header 
If you have access to your `.htaccess` on your hosting you can add the following code to redirect the links to your homepage
`
<IfModule mod_alias.c>
RedirectMatch 301 /xmlrpc.php /
</IfModule>
`
You can do the same for the Windows Live Writer XML and feed.

### I like all of the features in this plugin except for (insert_feature_here), how can I disable it? 
It's fairly simple. You can disable the function by doing the following:

1. Open up the plugin's main file `disable-blogging.php` to edit
1. At the beginning of the code you will see `// ADD ACTIONS` and `// ADD FILTERS`
1. Comment out one of the actions/filters using `//` in the beginning of the line to comment it out
1. Save the file and the feature will be disabled

**Note**: This needs to be done every time the plugin updates.

### I notice that there are still some blogging functions on WordPress, such as (insert_blogging_function_here) 
This plugin tries its best to disable all blogging related features, if something is missed, please mention it in our [support forum](https://wordpress.org/support/plugin/disable-blogging).

## Featured Articles

### NOUPE
http://www.noupe.com/wordpress/best-may-2016-ten-free-wordpress-plugins.html
### WP Tavern
https://wptavern.com/easily-hide-wordpress-blogging-features-with-the-disable-blogging-plugin
### DevotePress
http://devotepress.com/wordpress-news/disable-blogging-hide-wordpress-blogging-features/
### Hongkiat
http://www.hongkiat.com/blog/designers-developers-monthly-08-2016/
### WPcanban
http://wpcanban.com/wordpress/thu-thuat-wordpress/vo-hieu-hoa-tinh-nang-viet-bai-trong-wordpress.html
### Devid Bisset
https://twitter.com/dimensionmedia/status/755962357516558336