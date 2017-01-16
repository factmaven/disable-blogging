=== Disable Blogging in WordPress ===
Contributors: factmaven, ethanosullivan
Donate link: https://www.factmaven.com/
Tags: admin footer, admin menu, author pages, blog, comments, dashicons, disable author pages, disable blog, disable blogging, disable emoji, disable feeds, disable pingback, disable trackback, disable wordpress blogging, disable xml-rpc, disable xmlrpc, emoji, feeds, help tab, hide admin menu, hide dashicons, hide menu separators, hide user profile fields, howdy, menu separators, pingback, posts, query strings, remove help tab, remove howdy, remove query strings, separators, trackback, user profile, wordpress, xml-rpc, xmlrpc
Requires at least: 3.7.0
Tested up to: 4.7.1
Stable tag: 2.0.4
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Disable posts, comments, feeds, and other related the blogging features. A must have plugin to turn WordPress into a non-blogging CMS platform.

== Description ==
> Turn WordPress into a non-blogging CMS platform. **Disable Blogging** is a plugin that disables all blog related functionalities (posts, comments, feeds, etc.) on the front-end and back-end. This results in a cleaner and simpler WordPress platform to be used for static websites.

= Disable all posting & comments functions =
At its core level, all posting and comment related functionalities are disabled - but that's not all. **Disable Blogging** includes 20+ additional features to disable and hide cumbersome functions that run on WordPress including:

* Disable [Author](https://codex.wordpress.org/Author_Templates#Introduction) pages
* Disable [feeds](https://codex.wordpress.org/WordPress_Feeds#Introduction_to_Feeds), [pingbacks](https://codex.wordpress.org/Glossary#Pingback), [trackbacks](https://codex.wordpress.org/Glossary#Trackback), and [XML-RPC](https://codex.wordpress.org/XML-RPC_Support)
* Remove [Screen Options](http://www.wpbeginner.com/glossary/screen-options) and [Help](https://codex.wordpress.org/Class_Reference/WP_Screen/add_help_tab#Description) tabs from the admin header
* Remove the admin bar greeting next to the username
* Remove query strings from CSS & JS files
* Remove extra code from the header for [emoji support](https://codex.wordpress.org/Emoji)
* Simplify user profile
* [*and so much more...*](https://wordpress.org/plugins/disable-blogging/screenshots/)

= Notice =
This plugin does not delete any data on WordPress. It simply hides and disables those features that are blog related. If you have any existing, posts, comments, categories and tags on your website; they must be manually deleted if you do not want to keep any of that information. All plugin features can be enabled or disabled in the plugin's settings (*Settings* > *Blogging*).

Links to previous posts will still be accessible and previous comments will be hidden from view.

= Contribute on GitHub =
Want to help improve this plugin? Head over to our [GitHub page](https://github.com/factmaven/disable-blogging) and get listed as a [contributor to our plugin](https://wordpress.org/plugins/disable-blogging/other_notes)!

== Installation ==
1. Upload the plugin to the `../wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.

= Settings Page =
By default all blogging functionality are disabled. However, these options can be toggled in the plugin's *Settings* > *Blogging*.

== Frequently Asked Questions ==
= Are these changes permanent? =
No, this plugin simply disables, hides, and redirects all of the blogging functions. You can easily revert WordPress back to a blogging platform by simply disabling the plugin.

= How do I find the label IDs to hide additional profile fields? =
1. Navigate to the `Users` > `Your Profile`
1. Open up your browser's web inspector (ex: [Chrome](https://developer.chrome.com/devtools))
1. Use the element selector and select the label on the left side of field that you want to hide, for example:
  * `<label for="first_name">First Name</label>`
1. For each `<label for=` tag, you will find the ID's of each field, for example:
  * `first_name`

== Screenshots ==
1. General settings to toggle which blog functions to disable.
1. Extra features to disable. Not necessarily blog related.
1. Hide unused fields and options from the `Profile` page to reduce clutter.
1. Hide unused menu items created by plugins or themes, and redirect them elsewhere.

== Changelog ==
= 2.0.4 =

*2017-01-14*

* Screen Options tab can now be removed from the admin header
* Both `post` and `comments` are removed from the [REST API](https://wordpress.org/plugins/rest-api)
* Hiding additional admin menu items is no longer available
* Removed depreciated plugin option from v1.3.0
* **Fix**: fatal error with WooCommerce setup ([issue #12](https://github.com/factmaven/disable-blogging/issues/12))

= 2.0.3 =
*2016-11-26*

* **Fix**: fatal error given on some websites (thanks to [CotswoldPhoto](https://profiles.wordpress.org/cotswoldphoto))

= 2.0.2 =
*2016-11-25*

* i18n support: available in Japanese
* Disable alternative "Howdy" greetings in different languages (thanks to [Maël Conan](https://profiles.wordpress.org/maelconan))
* **Menu Feature**: option to disable reordering of Pages menu
* **Fix**: empty array error given for some websites
* **Fix**: invalid argument supplied for `foreach()` warning ([issue #10](https://github.com/factmaven/disable-blogging/issues/10))

= 2.0.1 =
*2016-10-23*

* **Fix**: website would load a blank page when disabling feeds

= 2.0.0 =
*2016-10-23*

* Rebuilt plugin with new Settings API, everything is now 100% customizable
* Reordered "*Pages*" menu further up underneath the "*Dashboard*" (thanks to [Piet Bos](https://github.com/senlin))
* The "*Activity*" meta box will show custom post types instead (thanks to [SECT](https://github.com/sectsect))
* **Extra Features**
  * Remove code in header used to add support for [emojis](https://codex.wordpress.org/Emoji)
  * Change or remove the admin footer
* **Profile Features**
  * Hide additional profile fields created by plugins/theme by their label ID
* **Menu Features**
  * Option to hide [dashicons](https://developer.wordpress.org/resource/dashicons)
  * Option to remove separators
* Blog related options are hidden from the Settings
* Set the following blog options in the Settings:
  * **Reading**: Set default the reading settings to a static page
  * **Discussion**: Unchecked "*attempt to notify any blogs linked to from the article*"
  * **Discussion**: Unchecked "*allow link notifications from other blogs (pingbacks and trackbacks) on new articles*"
* More extensive features added to disable blog related features
* Various code improvements
* **Fix**: comments column showing up in *Pages*
* **Fix**: custom `Taxonomy` redirecting to *Pages* upon update ([issue #3](https://github.com/factmaven/disable-blogging/pull/3))

The rest of the changelog can be [viewed on GitHub](https://github.com/factmaven/disable-blogging/blob/master/CHANGELOG.md).

== Upgrade Notice ==
= 2.0.4 =
Upgrading will reset all the settings to their default values.

== Contributors ==
We'd like to thank those who've helped improve our plugin:

* [Piet Bos](https://github.com/senlin)
* [SECT](https://github.com/sectsect)
* [Christian Jongeneel](https://profiles.wordpress.org/cjbj)
* [John A. Huebner II](https://github.com/Hube2)
* [Maël Conan](https://profiles.wordpress.org/maelconan)
* [CotswoldPhoto](https://profiles.wordpress.org/cotswoldphoto)

As well as those who've spotted bugs for us:

* [Benjamin Danon](https://github.com/sphax3d)
* [Saumya Majumder](https://github.com/isaumya)
* [youpain](https://profiles.wordpress.org/youpain)

= Contribute on GitHub =
Want to help improve this plugin? Head over to our [GitHub page](https://github.com/factmaven/disable-blogging).

[Current contributors](https://wordpress.org/plugins/disable-blogging/other_notes).