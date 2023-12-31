<?php

function zz_settings_page_html()
{
  $expo_token = get_option(EXPO_ACCESS_TOKEN_OPTION_NAME, "");
  $app_scheme = get_option(EXPO_APP_SCHEME, "");

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
          <label for="zz_expo_app_scheme">
            <strong>Add scheme</strong>
          </label>
        </div>
        <input name="zz_expo_app_scheme" id="zz_expo_app_scheme" type="text" placeholder="Application scheme like http, ftp.." value="<?= $app_scheme ?>" />
      </div>
      <div>
        <div>
          <label for="zz_expo_access_token">
            <strong>Expo access token</strong>
          </label>
        </div>
        <input name="zz_expo_access_token" id="zz_expo_access_token" type="text" placeholder="Expo push notification access token" value="<?= $expo_token ?>" />
      </div>
      <div>
        <input name="_wpnonce" type="hidden" value="<?= wp_create_nonce("zz_save_settings_nonce") ?>" />
      </div>
      <?php
      submit_button(__("Save"));
      ?>
    </form>
  </div>
<?php
  $_SESSION["_zzenz_notice"] = NULL;
}

/**
 * Save the details about the form from above by handling
 * the submit event
 */
function zz_settings_page_submit()
{
  if (
    'POST' === $_SERVER['REQUEST_METHOD'] &&
    isset($_POST["_wpnonce"]) && wp_verify_nonce($_POST["_wpnonce"], "zz_save_settings_nonce") &&
    isset($_POST["zz_expo_access_token"]) && isset($_POST["zz_expo_app_scheme"])
  ) {

    $existing_expo_token = get_option(EXPO_ACCESS_TOKEN_OPTION_NAME, "");
    $existing_app_scheme = get_option(EXPO_ACCESS_TOKEN_OPTION_NAME, "");
    $changes_needed = ($existing_expo_token !== $_POST["zz_expo_access_token"]
      || $existing_app_scheme !== $_POST["zz_expo_app_scheme"]
    );

    // do not try to save the option if its didnt change
    if ($changes_needed) {
      $is_saved_new_token      = update_option(EXPO_ACCESS_TOKEN_OPTION_NAME, $_POST["zz_expo_access_token"], FALSE);
      $is_saved_new_app_scheme = update_option(EXPO_APP_SCHEME, $_POST["zz_expo_app_scheme"], FALSE);
      $is_saved                = $is_saved_new_app_scheme || $is_saved_new_token;
    } else {
      $is_saved = TRUE;
    }

    if ($is_saved) {
      $_SESSION["_zzenz_notice"]['message'] = __("Saved successfully");
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
