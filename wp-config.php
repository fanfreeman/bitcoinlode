<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'lode');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'NWDpN.w;O6PH/,l}|0=O8^x7!ot Cn%yp#%P&r|Q#X#{;=%ixL C+K2vNG_Rl^d?');
define('SECURE_AUTH_KEY',  'pQf~,(}Xxv~t#qK]),7wB<on{WiB$YqJD]FP .=[|1yl@T^I/1AQXyZ,g5r&qkD_');
define('LOGGED_IN_KEY',    '`H~ELj??9)Rm.q}4Z5Daw=v8tjfH)YQ1X=77dT=16@[Hbse[t`ayHLpxN>7FN^V+');
define('NONCE_KEY',        'Db/q|&6z*AZx`H@oB6yhUK6^pHe<+VAC!?1]Ok|4$nO+]G=jPqjwAb_%A,Zxkrof');
define('AUTH_SALT',        '[yEb{tGBImv_SfvD`PeOUl)QBOB%t@@yLFx3@u[(aGm&>UOJBq:p_YNBePDVV/(L');
define('SECURE_AUTH_SALT', 'uqRz{s&;ZbkF{e=VgHmmqu:yUM4XH>i&2pzgZ9N7MBKENr?1~V~?pq? |!+h/jW_');
define('LOGGED_IN_SALT',   'pMQ0;Sr-n98:c4r)qJH%[%[fZI]ve,Sj[R5RYrd7b&/FFETLt#b!|Lig~/e5k)F?');
define('NONCE_SALT',       'N97XI}ujaYQJ;Td52aww7qson=_TGD@.{l,y`:9H,7qSvnS!<[.7yF4@7B=[<]S_');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
