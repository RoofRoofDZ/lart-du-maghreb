<?php

// BEGIN iThemes Security - Do not modify or remove this line
// iThemes Security Config Details: 2
define( 'DISALLOW_FILE_EDIT', true ); // Disable File Editor - Security > Settings > WordPress Tweaks > File Editor
// END iThemes Security - Do not modify or remove this line

define( 'ITSEC_ENCRYPTION_KEY', 'ay4/Wy8mSXVQJnJWb2MrVz4pciZFVGhiLUA5bGBCMT9vflVaPDdZMz4pSjJlZGVqbntGV00mO15BSiFwT0JtUQ==' );

define( 'WP_CACHE', true );

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'i%tB`+Eaz.&!g[VSL%R{*SvT:jf^Pf]mqX.$%Y;,FoXY(SDK0=H}90m>cjFXZIV=' );
define( 'SECURE_AUTH_KEY',   'NiwG!;5MbZVvHcxto|bz+ 5)TLa/oZhLU&>Sr@hQ%&:Z5#Ue6f;>!~MR)H>LYUgQ' );
define( 'LOGGED_IN_KEY',     ')>u]Fb)E@y|cs:P<eCUxEb`b&up8NPt1 ]*%Z>EjD=c>;O/MQL>27G}i( |qzdWo' );
define( 'NONCE_KEY',         '5bZS6ti.h7ZW4)/9a}|qs$rXcHOtJ-N<N2m!mZEyVmbj`DPCn4#)EVxG4C_2<Q#o' );
define( 'AUTH_SALT',         'g/Io@YeR]DIh@!=7DdM5{]hAE+J3GIl<OtJHbs?ho3^xymJeG0$9y`>^=9}]r_)=' );
define( 'SECURE_AUTH_SALT',  'D9r#2IOyJ[a1-nGT:x;:MQ7e:RN.Zm65()(Jigs*vio&HcP7$W|mrrXistG`P7Bn' );
define( 'LOGGED_IN_SALT',    '7y6MqAYy~U2E03R9L%I=gx934h]^#BuVJ//e#.~#AEB<A)K%^9sNPqO`$%3aj[~t' );
define( 'NONCE_SALT',        'OcE<ubT0(R(_;Tr%aYzMrjj@iV;_{P3&95-%@wn6u^5E86f.7GK)3ODjYMn)HHwm' );
define( 'WP_CACHE_KEY_SALT', 'yb9_Bjk>XwY2t%o7pL{3/m-}(?B,D]@B~fhh4IN5^g<b=c1a@}Z^c@kPpK0B]O=*' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
