<?php

/**
 * Plugin Name: Expo Push Notification For Wordpress
 * Plugin URI: https://github.com/siemah/zz-notification
 * Description: This Expo Plugin for WordPress is designed to simplify the integration of push notifications to all Android and iOS devices. With ease of use in mind
 * Version: 1.0.0
 * Author: Siemah<hmi.dayenio@gmail.com>
 * Author URI: https://github.com/siemah/
 * 
 */

include __DIR__ . "/admin/menu.php";
include __DIR__ . "/api/user-notification-token.php";

// the name of the expo access token in the website option
define("EXPO_ACCESS_TOKEN_OPTION_NAME", "zz_expo_access_token");
/**
 * name of the user mobile app token for its push notification
 * will used as a meta_key in the usermeta table
 */
define("USER_PUSH_NOTIFICATION_NAME", "zz_expo_push_notification_user_token");
