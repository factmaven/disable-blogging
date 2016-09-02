<?php
/**
    Plugin Name: * Disable Blogging
    Plugin URI: https://www.factmaven.com/#plugins
    Description: Disables posts, comments, feeds, and other related the blogging features. A must have plugin to turn WordPress into a non-blogging CMS platform.
    Author: <a href="https://www.factmaven.com/">Fact Maven</a>
    License: GPLv3
    Text Domain: disable-blogging
    Version: 2.0.0
 */

require_once dirname( __FILE__ ) . '/src/settings-api.php';
require_once dirname( __FILE__ ) . '/src/settings-page.php';

new Fact_Maven_Disable_Blogging();