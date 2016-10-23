# Disable Blogging [![Version](https://img.shields.io/wordpress/plugin/v/disable-blogging.svg?style=flat-square)](https://wordpress.org/plugins/disable-blogging/)
---
![Rating](https://img.shields.io/wordpress/plugin/r/disable-blogging.svg?style=flat-square) ![Downloads](https://img.shields.io/wordpress/plugin/dt/disable-blogging.svg?style=flat-square)

> Turn WordPress into a non-blogging CMS platform. **Disable Blogging** is a plugin that disables all blog related functionalities (posts, comments, feeds, etc.) on the front-end and back-end. This results in a cleaner and simpler WordPress platform to be used for static websites.

### Disable all posting & comments functions 
*Posts*, *Comments*, and other blog related menu items are removed from sidebar and toolbar and redirected to *Pages* menu. Additionally, it also changes the following:
* Removes blog related [widgets](https://codex.wordpress.org/WordPress_Widgets)
* Removes blog related meta boxes on the *Dashboard*
* Removes *Posts* and *Comments* columns
* Disable [Press This](https://codex.wordpress.org/Press_This) and [post-by-email](https://codex.wordpress.org/Post_to_your_blog_using_email)
* Change & hide all blogging options in WordPress' settings
* *and so more...*

**Note**: Links to previous posts will still be accessible and previous comments will be hidden from view.

### Disable author pages 
All author pages (`../author=?`) redirect to the the homepage. This helps prevent user enumeration - a common technique hackers use to revel usernames.

### Disable all feeds & related 
This includes [pingbacks](https://codex.wordpress.org/Glossary#Pingback), [trackbacks](https://codex.wordpress.org/Glossary#Trackback), and [https://codex.wordpress.org/XML-RPC_Support].

### Simplify user profile page 
Hide unused fields and options from the `Profile` page to reduce clutter. This includes custom fields created by plugins and themes.

### Simplify the admin menu 
Hide unused menu items created by plugins or themes, and redirect them elsewhere. Additional options include:
* Hide menu [dashicons](https://developer.wordpress.org/resource/dashicons)
* Hide menu separators, which is the spacing between some of the menu items

### Extra features include 
* Remove "*Help*" tabs from the admin header
* Remove the "*Howdy,*" greeting in the admin bar next to the username
* Have query string version removed form static resources
  * **Before**: `../twentysixteen/style.css?ver=4.6.1`
  * **After**: `../twentysixteen/style.css`
* Remove code in header used to disable for [emoji](https://codex.wordpress.org/Emoji) support
* Remove or modify the admin footer

### Notice 
This plugin does not delete any data on WordPress. It simply hides and disables those features that are blog related. If you have any existing, posts, comments, categories and tags on your website; they must be manually deleted if you do not want to keep any of that information. All plugin features can be enabled or disabled in the plugin's settings (*Settings* > *Blogging*).

### Contribute on GitHub 
Want to help improve this plugin? Head over to our [GitHub page](https://github.com/factmaven/disable-blogging). A special thanks to those who've contributed so far: [Piet Bos](https://github.com/senlin), [SECT](https://github.com/sectsect), [cjbj](https://wordpress.org/support/profile/cjbj)

## Installation 
1. Upload the plugin to the `../wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.

### Settings Page 
By default all blogging functionality are disabled. However, these options can be updated in the plugin's *Settings* > *Blogging*.

## Frequently Asked Questions 
### Are these changes permanent? 
No, this plugin simply disables, hides, and redirects all of the blogging functions. You can easily revert WordPress back to a blogging platform by simply disabling the plugin.

### How do I find the label IDs to hide additional profile fields? 
1. Navigate to the `Users` > `Your Profile`
1. Open up your browser's web inspector (ex: [Chrome](https://developer.chrome.com/devtools))
1. Use the element selector and select the label on the left side of field that you want to hide, for example:
  * `<label for="first_name">First Name</label>`
4. For each `<label for=` tag, you will find the ID's of each field, for example:
  * `first_name`

### How do I find the slug name to hide additional admin menu items? 
1. Navigate to the parent menu that you want to hide
1. If you look at the URL of the menu item, you will see the menu slug, for example:
  * `http://example.com/wp-admin/upload.php` (Media)
  * `http://example.com/wp-admin/admin.php?page=custom-plugin`
3. At the end of each URL you will list them as the following in the option:
  * `upload.php`
  * `custom-plugin`

**Note**: Currently only the parent menu items can be hidden, entering the submenu slugs will not work.