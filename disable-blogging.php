<?php
/**
 * Plugin Name: Disable Blogging
 * Plugin URI: https://www.factmaven.com/#plugins
 * Description: Turn WordPress into a non-blogging, CMS platform by disabling posts, comments, feeds, and other related the blogging features.
 * Author: <a href="https://www.factmaven.com/">Fact Maven</a>
 * License: GPLv3
 * Text Domain: disable-blogging
 * Version: 2.0.0
 */

require_once dirname( __FILE__ ) . '/includes/settings-api.php';
require_once dirname( __FILE__ ) . '/includes/settings-page.php';
require_once dirname( __FILE__ ) . '/includes/plugin-meta.php';
require_once dirname( __FILE__ ) . '/includes/functions-general.php';
require_once dirname( __FILE__ ) . '/includes/functions-profile.php';


require_once dirname( __FILE__ ) . '/includes/example.php';

new Fact_Maven_Disable_Blogging();