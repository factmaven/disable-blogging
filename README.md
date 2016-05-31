# Disable Blogging 
**Contributors:** factmaven
  
**Tags:** blog, posts, comments, disable blog, disable blogging
  
**Requires at least:** 3.0.1
  
**Tested up to:** 4.5.2
  
**Stable tag:** 1.1.0
  
**License:** GPLv2
  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html
  

Disables posts, comments, and other related the blogging features from WordPress, 'nuff said.


## Description 
If you don't use the blogging functionality of WordPress, this plugin helps simplify the website by:

* Disable and hide access to the `Posts` and `Comments` menu items
* Redirects `Posts` and `Comments` menu to `Pages` menu
* Remove blogging menu items from toolbar (admin bar)
* Remove Comments columns from pages
* Redirect `Dashboard` to `Pages`
* Remove feed links and related types
* Remove query strings from static resources


### Disclaimer 
This plugin does not delete any information, scripts, data, etc. on WordPress' core files and database. It simply hides and disables those features that are blog related.


### Fork Me on GitHub 
[View this plugin on GitHub](https://github.com/factmaven/disable-blogging)


## Installation 
1. Upload the plugin to the `/wp-content/plugins/` directory.
1. Activate the plugin through the `Plugins` menu in WordPress.
1. Let it settle in a for a minute and be amazed.

No settings or configuration needed, activate and enjoy.


## Frequently Asked Questions 

### Are these changes permanent? 
No, this plugin simply disables, hides, and redirects all of the blogging functions. You can easily revert back by simply disabling the plugin.


### I like all of the features in this plugin except for (insert_feature_here), how can I disable it? 
It's fairly simple. You can disable the function by doing the following:

1. Open up the plugin's main file `disable-blogging.php` to edit
1. At the beginning of the code you will see `// ADD ACTIONS` and `// ADD FILTERS`
1. Comment out one of the actions/filters using `//` in the beginning of the line to comment it out
1. Save the file and the feature will be disabled

**Note**: This needs to be done every time the plugin updates.


### I notice that there are still some blogging functions that I still see 
This plugin tries its best to disable all related features, if something is missed. Please mention it in our [Support forum](https://wordpress.org/support/plugin/disable-blogging)


## Screenshots 
1. Before
2. After


## Changelog 

### 1.1.0 
* 00/00/16
* Updated `readme.txt` and added `README.md` (GitHub)
* Added plugin meta links
* Removes "Howdy," from the admin bar
* Hide certain fields from user profile
* Hide default user roles (except admin)
* Feed links redirect to homepage if visited
* Hide help tabs in admin dashboard
* Disable "Press This"
* Disable blog related widgets
* Disable pings & trackbacks
* Disable post revisions
* Disable XML-RPC
* Various code improvements


### 1.0.0 
* 05/18/16
* Initial release, huzzah!