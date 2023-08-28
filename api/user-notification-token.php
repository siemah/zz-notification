<?php

use ExpoSDK\Expo;

add_action('rest_api_init', 'zz_expo_notification_setup_rest_routes');

/**
 * Extends rest api by adding new route to set users tokens
 */
function zz_expo_notification_setup_rest_routes()
{
  register_rest_route(
    'zz-expo-notification/v1',
    '/token',
    array(
      'methods' => 'POST',
      'callback' => 'zz_save_vendor_push_notification_token',
    )
  );
}


/**
 * Get loggedin customer
 */
function get_logedin_customer($cookie)
{
  $user = wp_parse_auth_cookie($cookie, "logged_in");

  if ($user !== FALSE) {
    $user        = get_user_by("login", $user["username"]);
    $customer_id = $user->get("id");

    return [
      "id" => $customer_id
    ];
  }

  return NULL;
}


/**
 * Save push notification token
 */
function zz_save_vendor_push_notification_token($request)
{
  $user     = get_logedin_customer($request->get_header("set-cookie"));
  $token    = $request->get_param('token');
  $response = new WP_REST_Response(
    [
      "code"   => "success",
      "status" => 200,
    ],
    200
  );

  if ((new Expo)->isExpoPushToken($token) === FALSE || $user === NULL) {
    $response->set_status(403);
    $response->set_data([
      "status" => 403,
      "code"   => "unauthorized",
    ]);
  } else {
    $user_id  = $user["id"];
    $is_saved = update_user_meta(
      $user_id,
      USER_PUSH_NOTIFICATION_NAME,
      $token
    );

    if ($is_saved === FALSE) {
      $response->set_status(400);
      $response->set_data([
        "status" => 400,
        "code"   => "failed",
      ]);
    }
  }

  return $response;
}
