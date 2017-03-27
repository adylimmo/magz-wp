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
define('DB_NAME', 'fix-theme-fashion');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'l3qwwt{{;#4W#Vx&/){#ZRr!pdEwR*4fM!_!IusFxIwA}l[dXc`gt}?5.k-F{]9p');
define('SECURE_AUTH_KEY',  'ge]Uo5ZFE1-agfk`K?FY{&u%2br/4XRNj0BuIA6&~&0!n*v?BTVu8&EQv1:8^?<7');
define('LOGGED_IN_KEY',    'WaElm%Nt!rwMJN}Z;pC|Fh}=Z=(2d|m5#p21>He{Tv&QDr2lKG;s}/o;k@TBZcll');
define('NONCE_KEY',        'eSSqU`r=G/HX6WxY6@h0QK46Q[^(;)aRNq?+_kKZbW![v=4muOAxa>9gi8%}?z/%');
define('AUTH_SALT',        'G|B(<UID]cEo1bymW~`{00nyVA}R6mVjH7>2,iGf/G8tL2_74GZmpo+a3v0JGF;l');
define('SECURE_AUTH_SALT', 'J2iJBsr,H1Vz-Y.(rB|/+fqC0+I~[=CZ*9nA2X(zRL{ cooiW2CBm/t(.K4Uj=|k');
define('LOGGED_IN_SALT',   'E)W[RoMvv&Q#PO;B^gIPnUct2X4~4ybXPmL|eU_}%Q.I*X5&_$;m?GHY+_#j` Oo');
define('NONCE_SALT',       '*eN^PTk{]B*74j<imX3-r4Us@0[Ag`^QP-5O{^H>T4Vk(#mqv=V[SNgVo1jC~6V3');

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
