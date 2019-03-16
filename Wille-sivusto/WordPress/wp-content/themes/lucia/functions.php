<?php

define( 'LUCIA_VERSION', '1.0.3' );
define( 'LUCIA_TEXTDOMAIN', 'lucia' );
define( 'LUCIA_THEME_DIR', get_template_directory() . '/' );
define( 'LUCIA_THEME_URI', get_template_directory_uri() . '/' );

if( !function_exists('is_plugin_active') ) {

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

}

// Helper library for the plugin install.
require_once LUCIA_THEME_DIR . '/inc/plugin-install/class-plugin-install-helper.php';

// Helper library for the theme customizer.
require_once LUCIA_THEME_DIR . '/inc/kirki-framework/kirki.php';

require_once LUCIA_THEME_DIR . '/inc/customizer-options.php';

// Theme setup.

require_once LUCIA_THEME_DIR . '/inc/core/theme-setup.php';

// Enqueue scripts and styles.

require_once LUCIA_THEME_DIR . '/inc/core/enqueue-scripts.php';

// Common-functions

require_once LUCIA_THEME_DIR . 'inc/core/common-functions.php';

// Custom template tags for this theme.

require_once LUCIA_THEME_DIR . 'inc/core/template-tags.php';

// Register widget area.

require_once LUCIA_THEME_DIR . 'inc/core/widgets.php';