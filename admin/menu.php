<?php

include_once __DIR__ . "/ui/home.php";

add_action("admin_menu", "zz_expo_notification_home_page");
function zz_expo_notification_home_page()
{
  // create menu
  add_menu_page(
    "Push notification for mobile app",
    "Home",
    "create_users",
    "zz-expo-notification",
    "zz_home_page_html",
    plugin_dir_url(__FILE__) . "../images/menu-icon.png",
    20,
  );
  // home page
  add_submenu_page(
    "zz-expo-notification",
    "Settings",
    "Settings",
    "create_users",
    "zz-expo-notification-settings",
    "zz_home_page_html",
  );
  // add_submenu_page(
  //   "zz-expo-notification",
  //   "Settings",
  //   "Settings",
  //   "create_users",
  //   "zz-expo-notification-settings",
  //   "zz_home_page_html",
  // );
}
