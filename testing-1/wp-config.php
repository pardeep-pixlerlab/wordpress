<?php
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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'testing' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'ARAtr}[ Y7g[e$k&@QB26&sKpGuikH|{b&g6 yd{6~>|ZWbII-b=BA@M`8LAC~2B' );
define( 'SECURE_AUTH_KEY',  '^_vXi`of=[U%$qKj/k`0AseT][R&~?E*WG66cP5J0o1 KLeA~h,2,w 9G`&v[HF,' );
define( 'LOGGED_IN_KEY',    '>P$ Sf<ScdO=DwU.,A14S*#fTxHGX;d2@$V`<:dte<)R|Lo|uy1zS}PE1$J^*}7z' );
define( 'NONCE_KEY',        'm/L $%Q?$X2^iM.4r{SQ!>v_t_PFqHvMUt(Q9e*u~=Rw(BeqrQ>gD)VKa}]ktMo3' );
define( 'AUTH_SALT',        '_N[x:[QXR-`[k[Vt1NU6O.k)q;+5i|m7r`j|M/zSfu^(fU5)mX_?S<1vorl4+o.L' );
define( 'SECURE_AUTH_SALT', ';68H8+M!Wyu%wlhQD-_,XQ +[&MQ Nv}|( 9>nbzYnHAW}LzKHU;},FYjl5b#O 3' );
define( 'LOGGED_IN_SALT',   'B#>+HDT^6b] r%?~@s>5Zs2>%q+cRj8A?Nm~fVn=2{=[m8s.o_:ezT UhmFp>&Tb' );
define( 'NONCE_SALT',       '#,YAAybaL|itBT@7{]9tFqE-~k6N`w/n;0s,OBpD}~%JG:_Sm}<T[9W($+S3[X( ' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'testing_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
