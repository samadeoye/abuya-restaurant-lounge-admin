<?php
require_once 'config.php';

define('APP_NAME', 'Abuya Restaurant & Lounge - Admin');
define('APP_EMAIL', 'hello@abuyarestaurantandlounge.com');
define('APP_PHONE', '+2349034770998');
define('APP_ADDRESS', "Lagos, Nigeria");
define('APP_DESC', "Control panel for ".APP_NAME);
define('APP_KEYWORDS', 'business, clients, management, tracking, sales');
define('APP_URL', 'http://localhost/abuya-admin');
define('MAIN_APP_URL', 'https://clientstouch.com');
define('APP_DOMAIN', 'clientstouch.com');
define('DEF_LOCAL_SERVER', 'http://localhost');
define('DEF_LIVE_SERVER', 'https://admin.abuyarestaurantandlounge.com');
define('APP_AUTHOR', 'Samuel Adeoye');
define('DEF_CURRENCY_SYMBOL', '₦');

//TABLES
define('DEF_TBL_BOOKINGS', 'bookings');
define('DEF_TBL_BOOKINGS_ITEMS', 'bookings_items');
define('DEF_TBL_BOOKINGS_CARTS', 'bookings_carts');
define('DEF_TBL_BOOKINGS_CARTS_ITEMS', 'bookings_carts_items');
define('DEF_TBL_SERVICES', 'services');
define('DEF_TBL_USERS', 'users');
define('DEF_TBL_PASSWORD_RESET', 'password_reset');