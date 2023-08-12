<?php

include_once __DIR__ . "/ui/home.php";
include_once __DIR__ . "/ui/settings.php";

add_action("admin_menu", "zz_expo_notification_home_page");
function zz_expo_notification_home_page()
{
  // create menu
  $send_notification_hookname = add_menu_page(
    "Push notification for mobile app",
    "Home",
    "create_users",
    "zz-expo-notification",
    "zz_home_page_html",
    plugin_dir_url(__FILE__) . "../images/menu-icon.png",
    20,
  );
  add_action("load-" . $send_notification_hookname, "zz_home_page_submit");
  // settings page
  $save_settings_details_hookname = add_submenu_page(
    "zz-expo-notification",
    "Settings",
    "Settings",
    "create_users",
    "zz-expo-notification-settings",
    "zz_settings_page_html",
  );
  add_action("load-" . $save_settings_details_hookname, "zz_settings_page_submit");
}
