## Changelog
### 2.0.0
* Rebuilt plugin with new Settings API, everything is now 100% customizable
** Profile settings page relocated to "Settings" > "Blogging"
* Reorder "Pages" menu further up (thanks to [Piet Bos](https://wordpress.org/support/users/senlin/))
* Built-in WordPress options in the settings are changed automatically:
** **Discussion**: Attempt to notify any blogs linked to from the article (unchecked)
* Blog-related options are removed from the settings
* Extra features added to disable pingbacks & trackbacks
* Option to hide admin icons is added
* Various code improvement and performance

### 1.3.0 (08/14/16)
* Added settings to toggle profile fields under "Users" > "Settings"
* Posts & comments column removed (`dsbl_page_comments` is now `dsbl_columns`)
* Author page is disabled and redirects to homepage (`dsbl_author_page` and `dsbl_author_link`)
* Remove WP Engine meta box (`dsbl_meta_boxes`)

### 1.2.5 (07/19/16)
* Restored "Email" field in user profile

### 1.2.4 (07/08/16)
* Simplified function to hide user profile fields

### 1.2.3 (06/30/16)
* "Dashboard" redirects to "Profile" menu instead of "Pages"

### 1.2.2 (06/25/16) 
* Fixed redirect loop from `dsbl_feeds` (now `dsbl_header_feeds`)
* The plugin's meta links function is in `includes/plugin-meta.php`
* Replace `dsbl_false_return` function with `__return_false` instead

### 1.2.1 (06/21/16) 
* Removed plugin directory define

### 1.2.0 (06/10/16) 
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

### 1.1.0 (05/31/16) 
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

### 1.0.0 (05/18/16) 
* Initial release, huzzah!