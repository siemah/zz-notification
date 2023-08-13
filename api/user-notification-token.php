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
 * Save push notification token
 */
function zz_save_vendor_push_notification_token($request)
{
  $user_id  = get_current_user_id();
  $token    = $request->get_param('token');
  $response = $response = new WP_REST_Response(
    [
      "code"   => "success",
      "status" => 200,
    ],
    200
  );

  if ((new Expo)->isExpoPushToken($token) === FALSE || !!$user_id === FALSE) {
    $response->set_status(403);
    $response->set_data([
      "status" => 403,
      "code"   => "unauthorized",
    ]);
  } else {
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
