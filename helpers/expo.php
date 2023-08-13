<?php

/**
 * Remove a expo push notification token
 * 
 * @param string $token the token to remove
 * @return true if removed otherwise false
 */
function remove_unregistred_token(string $token)
{
  global $wpdb;

  $sql_query = "SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta WHERE meta_value= %s AND meta_key = %s";
  $prepared_query = $wpdb->prepare($sql_query, [$token, USER_PUSH_NOTIFICATION_NAME]);
  $token_row = $wpdb->get_row($prepared_query);
  $is_removed = FALSE;
  // remove the push notification token
  if ($token_row !== NULL) {
    $is_removed = delete_user_meta($token_row->user_id, USER_PUSH_NOTIFICATION_NAME, $token_row->meta_value);
  }

  return $is_removed;
}

/**
 * Fetch all users tokens(expo push notification token)
 * 
 * @return array list of expo tokens
 */
function get_users_tokens()
{
  global $wpdb;

  $sql_query = "SELECT meta_value FROM {$wpdb->prefix}usermeta WHERE meta_key = %s";
  $prepared_query = $wpdb->prepare($sql_query, [USER_PUSH_NOTIFICATION_NAME]);
  $tokens = $wpdb->get_results($prepared_query);
  $results = [];

  if (count($tokens) > 0 && $tokens !== NULL) {
    $results = array_map(
      function ($token) {
        return $token->meta_value;
      },
      $tokens
    );
  }

  return $results;
}


/**
 * Cleanup unregister tokens of the push notification
 * 
 * @param Array $push_tickets list of the push tickets received after sending a notification
 * @return 
 */
function cleanup_unregistred_tokens(array $push_tickets)
{
  foreach ($push_tickets as $push_ticket) {
    $ticket_details = $push_ticket["details"];

    if ($ticket_details["error"] === "DeviceNotRegistered") {
      remove_unregistred_token($ticket_details["expoPushToken"]);
    }
  }
}
