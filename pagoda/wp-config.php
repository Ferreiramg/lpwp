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
define('DB_NAME', $_SERVER["DB1_NAME"]);

/** MySQL database username */
define('DB_USER', $_SERVER["DB1_USER"]);

/** MySQL database password */
define('DB_PASSWORD', $_SERVER["DB1_PASS"]);

/** MySQL hostname */
define('DB_HOST', $_SERVER["DB1_HOST"]);

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
define('AUTH_KEY',         'ltErNNCw5VZm6sMojU*naH:7 Nw_kbD83hcZa_e=<[K%UF[d!TR&rBhetOS3PqxB');
define('SECURE_AUTH_KEY',  '>q.pMZyl9^j?X=6vP#{MRtb@%?u/@MzFSX6i$,ARCK/N{s0Y?-S!~/hHVHUe_P<E');
define('LOGGED_IN_KEY',    'F}w-U$>a$S+wp$7BgXd!<4[R@S9/_lmqoT)0A0Q;`|F)oo/&<_jQ$|l7*>RF|^iP');
define('NONCE_KEY',        'J;Sw#AbbRSI<IeDIOr)wUl6IEE0U]~ApO&]@N^ofW&DCGaQ{19Ck~Azfv~,@x^~.');
define('AUTH_SALT',        'e$Gc-}2-c&oGj2Y.&+mK|k;l*)7u#~#fgGu;:z84>}f)3#4GNn%w{9WD!YW4U,eC');
define('SECURE_AUTH_SALT', '3dL@o[/h!Dwt,EFi;PPi3H$C`b/)SDx0R$&DM-/+DQpNxl6lyVTc5B|mE$f-9q:h');
define('LOGGED_IN_SALT',   'mJ?wUV[yj.]S^n,DmVP<~Zu{$dqyg g/N+(MM};-=+_w]Qvlco@xn%|0v[OmPI<$');
define('NONCE_SALT',       ';FCFRm80_On)742P==N+agCX)q9,$l}GIx%RsBw(c]1-BJ$+,FAH{SHQ(4-%BP)N');

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
define('WPLANG', 'pt_BR');

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