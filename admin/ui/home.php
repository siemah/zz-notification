<?php

use ExpoSDK\Expo;
use ExpoSDK\ExpoMessage;

require_once __DIR__ . '/../../libs/vendor/autoload.php';
include __DIR__ . "/../../helpers/expo.php";

/**
 * Plugin home page
 */
function zz_home_page_html()
{
?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <?php if (isset($_SESSION["_zzenz_notice"])) { ?>
      <div class="notice notice-<?= $_SESSION["_zzenz_notice"]['type'] ?> is-dismissible">
        <p><?= $_SESSION["_zzenz_notice"]['message']; ?></p>
      </div>
    <?php } ?>
    <form action="<?php menu_page_url("zzenz-add-plan") ?>" method="post">
      <div>
        <div>
          <label for="title">
            <strong><?= __("Title") ?></strong>
          </label>
        </div>
        <input name="title" id="title" type="text" placeholder="<?= __("Title") ?>" />
      </div>
      <div>
        <div>
          <label for="message">
            <strong><?= __("Message") ?></strong>
          </label>
        </div>
        <textarea name="message" id="message" cols="21" rows="5" placeholder="<?= __("Message") ?>"></textarea>
      </div>
      <div>
        <div>
          <label for="path">
            <strong><?= __("Path") ?></strong>
          </label>
        </div>
        <input name="path" id="path" type="text" placeholder="<?= __("Path") ?> /shop" />
      </div>
      <input name="_wpnonce" type="hidden" value="<?= wp_create_nonce("zz_send_notification_nonce") ?>" />
      <?php
      submit_button(__("Send"));
      ?>
    </form>
  </div>
<?php
}

/**
 * Send push notification to mobile app using Expo notification
 * 
 * @see https://docs.expo.dev/push-notifications/sending-notifications/#http2-api
 */
function zz_home_page_submit()
{
  $expo_access_token = get_option(EXPO_ACCESS_TOKEN_OPTION_NAME, "");

  if (
    'POST' === $_SERVER['REQUEST_METHOD'] &&
    isset($_POST["_wpnonce"]) && wp_verify_nonce($_POST["_wpnonce"], "zz_send_notification_nonce") &&
    !empty($_POST["title"]) && !empty($_POST["message"]) && !empty($_POST["path"]) &&
    $expo_access_token !== ""
  ) {
    extract($_POST);
    // get all users tokens
    $tokens = get_users_tokens();
    $messages = [];

    foreach ($tokens as $token) {
      $expoMessage = new ExpoMessage([
        'title' => $title,
        'body'  => $message,
        'to'    => $token
      ]);
      $user_message = $expoMessage->setData([])->playSound();
      array_push($messages, $user_message);
    }

    // send a push notification to each user
    $notification_response = (new Expo)
      ->setAccessToken($expo_access_token)
      ->send($messages)
      ->to($tokens)
      ->push();
    $push_tickets = $notification_response->getData();
    cleanup_unregistred_tokens($push_tickets);

    if (TRUE) {
      $_SESSION["_zzenz_notice"]['message'] = __("Send successfully");
      $_SESSION["_zzenz_notice"]['type'] = "success";
    } else {
      $_SESSION["_zzenz_notice"]['message'] = __("Something went wrong please check your inputs then try again!");
      $_SESSION["_zzenz_notice"]['type'] = "danger";
    }
  } else if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $_SESSION["_zzenz_notice"]['message'] = __("Something went wrong! Please check your inputs then try again.");
    $_SESSION["_zzenz_notice"]['type'] = "error";
  }
}
