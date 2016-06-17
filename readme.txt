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

= Menu, Submenu & Toolbar Items =
Remove items from sidebar and toolbar and redirect to _Page_ menu.

* **Main**: Dashboard (home), Posts, Comments
* **Tools**: Available Tools
* **Settings**: Writing, Discussion
* **Toolbar**: WordPress logo, Posts, Comments, Search

= Disable Post & Comments =
* Remove comments function from pages
* Remove blog related widgets

= Disable Feeds & Related =
* Disable pingbacks and trackbacks
* Disable XMLRPC
* Remove all feed links & redirect to homepage
* Remove blog related widgets

= Simplify User Profile =
Remove certain fields and options from the _User Profile_ making less clutter.

* **Personal Options**: Remove Visual Editor, Admin Color Scheme, Keyboard Shortcuts, Toolbar
* **Name**: Nickname, Display Name
* **Contact Info**: Website, Aim, Yahoo IM, Jabber, Google+, Twitter, Facebook
* **About Yourself**: Description
* **[Yoast SEO](https://wordpress.org/plugins/wordpress-seo)**: Google+, Twitter, Facebook, Author Page Title, Author Description

= Other Features =
* Disable themes & plugins editor
* Disable post revisions
* Remove "Howdy," on the upper right of the toolbar
* Remove "Help" tabs in upper right in the dashboard
* Disables "Press This" function
* Remove query strings (`ver=`) from static resources
* Hide default user roles (except admin)

= Notice =
This plugin does not delete any information, scripts, data, etc. on WordPress' core files and database. It simply hides and disables those features that are blog related.

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
* Restores default user roles
* All blog links redirect to "Pages" menu
* Update meta links on plugin page
* Deactivate other related plugins to prevent conflicts
* Remove comments column from "Media" menu
* Improved code structure and added plugin class

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