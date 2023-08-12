<?php

function zz_home_page_html()
{
?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <?php if (isset($_GET["_zzenz_notice_message"])) { ?>
      <div class="notice notice-<?= $_GET["_zzenz_notice_type"] ?> is-dismissible">
        <p><?= $_GET["_zzenz_notice_message"]; ?></p>
      </div>
    <?php } ?>
  </div>
<?php
}

?>