=== Disable Blogging in WordPress ===
Contributors: factmaven, ethanosullivan
Tags: wordpress, disable wordpress blogging, disable blogging, disable blog, disable feeds, feeds, blog, posts, comments, remove query strings, query strings, user profile, hide user profile fields, disable emoji, emoji, disable author pages, author pages, disable pingback, pingback, disable trackback, trackback, disable xml-rpc, xml-rpc, disable xmlrpc, xmlrpc, remove help tab, help tab, remove howdy, howdy, admin footer, hide dashicons, dashicons, hide menu separators, menu separators, separators, hide admin menu, admin menu
Requires at least: 3.7.0
Tested up to: 4.6.1
Stable tag: 2.0.2
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Disable posts, comments, feeds, and other related the blogging features. A must have plugin to turn WordPress into a non-blogging CMS platform.

== Description ==
> Turn WordPress into a non-blogging CMS platform. **Disable Blogging** is a plugin that disables all blog related functionalities (posts, comments, feeds, etc.) on the front-end and back-end. This results in a cleaner and simpler WordPress platform to be used for static websites.

= Disable all posting & comments functions =
*Posts*, *Comments*, and other blog related menu items are removed from sidebar and toolbar and redirected to *Pages* menu. Additionally, it also changes the following:

* Removes blog related [widgets](https://codex.wordpress.org/WordPress_Widgets)
* Removes blog related meta boxes on the *Dashboard*
* Removes *Posts* and *Comments* columns
* Disable [Press This](https://codex.wordpress.org/Press_This) and [post-by-email](https://codex.wordpress.org/Post_to_your_blog_using_email)
* Change & hide all blogging options in WordPress' settings
* *and so more...*

**Note**: Links to previous posts will still be accessible and previous comments will be hidden from view.

= Disable author pages =
All author pages (`../author=?`) redirect to the the homepage. This helps prevent user enumeration - a common technique hackers use to revel usernames.

= Disable all feeds & related =
This includes [pingbacks](https://codex.wordpress.org/Glossary#Pingback), [trackbacks](https://codex.wordpress.org/Glossary#Trackback), and [XML-RPC](https://codex.wordpress.org/XML-RPC_Support).

= Simplify user profile page =
Hide unused fields and options from the `Profile` page to reduce clutter. This includes custom fields created by plugins and themes.

= Simplify the admin menu =
Hide unused menu items created by plugins or themes, and redirect them elsewhere. Additional options include:

* Hide menu [dashicons](https://developer.wordpress.org/resource/dashicons)
* Hide menu separators, which is the spacing between some of the menu items

= Extra features include =
* Remove "*Help*" tabs from the admin header
* Remove the "*Howdy,*" greeting in the admin bar next to the username
* Have query string version removed form static resources
  * **Before**: `../twentysixteen/style.css?ver=4.6.1`
  * **After**: `../twentysixteen/style.css`
* Remove code in header used to disable for [emoji](https://codex.wordpress.org/Emoji) support
* Remove or modify the admin footer

= Notice =
This plugin does not delete any data on WordPress. It simply hides and disables those features that are blog related. If you have any existing, posts, comments, categories and tags on your website; they must be manually deleted if you do not want to keep any of that information. All plugin features can be enabled or disabled in the plugin's settings (*Settings* > *Blogging*).

= Contribute on GitHub =
Want to help improve this plugin? Head over to our [GitHub page](https://github.com/factmaven/disable-blogging).

[Current contributors](https://wordpress.org/plugins/disable-blogging/other_notes).

== Installation ==
1. Upload the plugin to the `../wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.

= Settings Page =
By default all blogging functionality are disabled. However, these options can be updated in the plugin's *Settings* > *Blogging*.

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

= How do I find the slug name to hide additional admin menu items? =
1. Navigate to the parent menu that you want to hide
1. If you look at the URL of the menu item, you will see the menu slug, for example:
  * `../wp-admin/upload.php` (Media)
  * `../wp-admin/admin.php?page=custom-plugin`
  * `../wp-admin/edit.php?post_type=custom-post-type`
1. At the end of each URL you will list them as the following in the option:
  * `upload.php`
  * `custom-plugin`
  * `edit.php?post_type=custom-post-type`

**Note**: Currently only the parent menu items can be hidden, entering the submenu slugs will not work.

== Screenshots ==
1. General settings to toggle which blog functions to disable.
1. Extra features to disable. Not necessarily blog related.
1. Hide unused fields and options from the `Profile` page to reduce clutter.
1. Hide unused menu items created by plugins or themes, and redirect them elsewhere.

== Changelog ==
= 2.0.2 =
*2016-11-23*

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

== Contributors ==
We'd like to thank those who've helped improve our plugin:

* [Piet Bos](https://github.com/senlin)
* [SECT](https://github.com/sectsect)
* [Christian Jongeneel](https://profiles.wordpress.org/cjbj)
* [John A. Huebner II](https://github.com/Hube2)
* [Maël Conan](https://profiles.wordpress.org/maelconan)

As well as those who've spotted bugs:

* [Benjamin Danon](https://github.com/sphax3d)
* [Saumya Majumder](https://github.com/isaumya)