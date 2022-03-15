<h1 class="ps-crypto-title"><?php _e('Cryptocurrency converter BTC to ETC') ?></h1>
<?php settings_errors(); ?>

<div class="ps-crypto-convert">
  <form class="ps-crypto-convert__form" action="options.php" method="post">
    <?php
    settings_fields('convert_settings');
    do_settings_sections('crypto_convert_settings');
    submit_button();
    ?>
  </form>
</div>
