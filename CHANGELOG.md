## Changelog
### 2.0.1 [2016-10-23]
**Fixes**:
* Website would load a blank page when disabling feeds

### 2.0.0 [2016-10-23]
**Improvements**:
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

**Fixes**:
* Comments column showing up in *Pages*
* Custom `Taxonomy` redirecting to *Pages* upon update ([issue #3](https://github.com/factmaven/disable-blogging/pull/3))

### 1.3.0 [2016-08-14]
* Added settings to toggle profile fields under "Users" > "Settings"
* Posts & comments column removed
* Author page is disabled and redirects to homepage
* Remove WP Engine meta box

### 1.2.5 [2016-07-19]
* Restored "Email" field in user profile

### 1.2.4 [2016-07-08]
* Simplified function to hide user profile fields

### 1.2.3 [2016-06-30]
* "Dashboard" redirects to "Profile" menu instead of "Pages"

### 1.2.2 [2016-06-25] 
* Fixed redirect loop from `dsbl_feeds` (now `dsbl_header_feeds`)
* The plugin's meta links function is in `includes/plugin-meta.php`
* Replace `dsbl_false_return` function with `__return_false` instead

### 1.2.1 [2016-06-21] 
* Removed plugin directory define

### 1.2.0 [2016-06-10] 
* Restored "Nickname" and "Display name" fields in user profile
* Restored default user roles
* Renabled theme & plugin editor
* Renabled post revisions
* All blog links redirect to "Pages" menu
* Update meta links on plugin page
* Deactivate other related plugins to prevent conflicts
* Remove comments column from "Media" menu
* Disable additional pingbacks
* Disable posting via email
* Improved code structure
* Improved other functions

### 1.1.0 [2016-05-31] 
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

### 1.0.0 [2016-05-18] 
* Initial release, huzzah!