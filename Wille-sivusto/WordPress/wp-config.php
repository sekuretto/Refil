<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'refil');

/** MySQL database password */
define('DB_PASSWORD', 'Refil2019');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD', 'direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */

define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('AUTH_KEY',         'go{7krp9L,/%l5%Zftkc^1.%6=6BGj#:4UiF}}Ly|L?T-1Os-m}>*AB!c)]a1D44');
define('SECURE_AUTH_KEY',  ']wp<+eb>+2.%$%d4(hC<7IlqK2eN+^igeF<jg>u<]ePLc+!jQsTPg?Usn9g78I [');
define('LOGGED_IN_KEY',    '7hyyx,g27U]S(jp=Z}{-lDi`D)?2{!p.[7}u[+-jJIZ3zioD={v eCkm(JXM.yZW');
define('NONCE_KEY',        '|dE,$.V1MN%B9pIt%@XPmws;Jy*i{K8st#6<-6bS<|icq:5|Mdg%[F|j6k31d=X.');
define('AUTH_SALT',        '3S3;j.H+7,1w.<;[NU|&n;|z+;zT_JmeR`1stq--n(,tS>k+:&aYAhH4|&OE|OYX');
define('SECURE_AUTH_SALT', '$H3E]|<y]Z-tv5,j<QhP*~F4Fwq-L[vN5vg-mcdm_Oti8s%S[iig_zf_J-<@jB91');
define('LOGGED_IN_SALT',   'Y.HnCe+C7X.%-EE7,~G^=.``<@B[|k>~? 1#Tc3@!ao}m~el|f!!-nt@Z)OJ %mg');
define('NONCE_SALT',       '~1s/0^-D1=E>DNa#yKMAaz>[g;$M=*DOedue|&)Vn<-G98:T4@r.$|S,*azy8+V/');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
