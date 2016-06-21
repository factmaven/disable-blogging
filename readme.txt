=== Disable Blogging ===
Contributors: factmaven
Tags: disable wordpress blogging, disable blogging, disable blog, disable feeds, feeds, blog, posts, comments, remove query strings, query strings
Requires at least: 4.5
Tested up to: 4.5.2
Stable tag: 1.2.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Disables posts, comments, and other related the blogging features from WordPress, 'nuff said.

== Description ==
**Disable Blogging** is a plugin that disables all blogging-related functionalities on WordPress on the front-end and the back-end. This makes for a cleaner and simpler WordPress platform to be used for static websites.

Below is a summary of what this plugin covers:

= Simplified and Cleaner Admin Dashboard =
* Removes "Postsmenu/submenu items & redirect to page menu
* Remove menu items from the toolbar
* Remove comments column from posts & pages
* Remove blog related widgets
* Disables "Press This" and redirect to homepage
* Remove help tabs
* Hide certain fields from user profile
* Removed "Howdy," from the admin bar, we ain't from Texas!
* Replace WordPress footer in dashboard with your site info

= Feeds & Related =
* Disables and remove all blogging feeds
* Disables and removes all pingbacks and trackbacks
* Disables and remove XML-RPC

= Other Features =
* Replaces theme's comments template with empty page
* Remove query strings from static resources

= Notice =
This plugin does not delete any information, scripts, data, etc. on WordPress' core files and database. It simply hides and disables those features that are blog related. You must manually delete your posts, comments, tags, and categories from your database if you do not want to keep any of them.

= Fork Me on GitHub =
[View this plugin on GitHub](https://github.com/factmaven/disable-blogging)

== Installation ==
1. Upload the plugin to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.

No settings or configuration needed, activate and enjoy.

== Frequently Asked Questions ==
= Are these changes permanent? =
No, this plugin simply disables, hides, and redirects all of the blogging functions. You can easily revert back by simply disabling the plugin.

= So these blogging functions are just hidden from view? =
No, they are also disabled from being access as well for added security. If someone were to access the *Posts* menu:
`wordpress.org/wp-admin/post.php`
They would be redirected to the *Pages* menu:
`wordpress.org/wp-admin/edit.php?post_type=page`

= I can still access the XML-RPC when I visit `myweb.site/xmlrpc.php` even through it's removed form my header =
If you have access to your `.htaccess` on your hosting you can add the following code to redirect the links to your homepage
`
<IfModule mod_alias.c>
RedirectMatch 301 /xmlrpc.php /
RedirectMatch 301 /wp-includes/wlwmanifest.xml /
</IfModule>
`
You can do the same for the Windows Live Writer XML which has also been added above.

= I like all of the features in this plugin except for (insert_feature_here), how can I disable it? =
It's fairly simple. You can disable the function by doing the following:

1. Open up the plugin's main file `disable-blogging.php` to edit
1. At the beginning of the code you will see `// ADD ACTIONS` and `// ADD FILTERS`
1. Comment out one of the actions/filters using `//` in the beginning of the line to comment it out
1. Save the file and the feature will be disabled

**Note**: This needs to be done every time the plugin updates.

= I notice that there are still some blogging functions on WordPress, such as (insert_blogging_function_here) =
This plugin tries its best to disable all blogging related features, if something is missed, please mention it in our [support forum](https://wordpress.org/support/plugin/disable-blogging).

== Screenshots ==
1. Before: admin sidebar and toolbar
2. After: admin sidebar and toolbar
3. Before: user profile
4. After: user profile

== Changelog ==
= 1.2.0 =
* 06/10/16
* Restored "Nickname" and "Display name" fields in user profile
* Restored default user roles
* Renabled theme & plugin editor
* Renabled post revisions
* All blog links redirect to "Pages" menu
* Update meta links on plugin page
* Deactivate other related plugins to prevent conflicts
* Remove comments column from "Media" menu
* Disable additional pingbacks
* Improved code structure
* Improved other functions

= 1.1.0 =
* 05/31/16
* Updated `readme.txt`
* Added plugin meta links
* Removes "Howdy," from the admin bar
* Hide certain fields from user profile
* Hide default user roles (except admin)
* Feed links redirect to homepage if visited
* Hide help tabs in admin dashboard
* Disable "Press This"
* Disable blog related widgets
* Disable pings & trackbacks
* Disable XML-RPC
* Disable post revisions
* Disable theme's comment template
* Various code improvements

= 1.0.0 =
* 05/18/16
* Initial release, huzzah!