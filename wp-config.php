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
define( 'DB_NAME', 'self-tastic.com' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'Nunx$:R<@7$TFt8|H<9Vm.x|Irk*e!#;)EWcl< .{D>AT:A^(*_Iv;$7p:FL8Oz]' );
define( 'SECURE_AUTH_KEY',  ';8uyxra_]_w4:2a*!2TcgStQ/lLPH^5*@2^!.Y@G*Uv?PB_J%)2&$A ZxYzvn0rL' );
define( 'LOGGED_IN_KEY',    'qaV_C1uV?EV e~m4)}a[-nfP,Xg^t|Zs8ULt2ch!lIH~9O,nmNtf`aJ DyTx_bQ.' );
define( 'NONCE_KEY',        'f:/mG<T[tgDbIDr4vnTY;HJdPTeI$NBhQrBn630bDfh*iP rvq2<^|n9GzIuf:JE' );
define( 'AUTH_SALT',        'Q|q2)^!TdcM1Ur5n>lCGIzvaBB6j04|T>McUi_l9PN;z*M VdE.^&E(=5#$fx]1Q' );
define( 'SECURE_AUTH_SALT', 'Y5>a&s/[T!vPbY*2l8J7PPL8;hS[7e0}6S.Gc+%kOOXMG8=au@M6XbC>o<3hB^,w' );
define( 'LOGGED_IN_SALT',   'LpZ|)|iCg(5m @{>K!GAmqZIv%3G}Ip2bU*^y+oBw7r~{7M>$E^99d~8$,gnHJs<' );
define( 'NONCE_SALT',       '9]&X,Mo?_D*?]Ac:LOI2wU~{kC7;+10;/G4kspHp~TQ^z_p? N%dH@g5m[$A`Z]I' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
