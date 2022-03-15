<?php
/**
 * Public main function
 **/
function ps_crypto_converter ()
{

  $options = get_option('convert_settings_options');
  $etc     = !empty($options['ETC']) ? round($options['ETC'], 4) : '';
  $btc     = $etc ? round(1 / $etc, 7) : '';
  ?>
  <div class="ps-cc" data-convert-main>
    <div class="ps-cc__row">

      <div class="ps-cc__box">

        <form action="" method="POST" class="ps-cc__form" data-convert-form="BTC">
          <h4 class="ps-cc__form-title"><?php _e('Convert BTC to ETC', 'ps-crypto-converter') ?></h4>

          <div class="ps-cc__form-item">
            <input type="number" class="ps-cc__form-input ps-cc__form-input_btc" value="1" data-convert-cur required>
            <div class="ps-cc__form-arrow"></div>
            <input type="text" class="ps-cc__form-input ps-cc__form-input_etc ps-cc__form-input_disabled" value="<?= $etc ?>" disabled data-convert-res>
          </div>

          <button class="ps-cc__form-btn" data-convert-btn><?php _e('Convert', 'ps-crypto-converter') ?></button>
        </form>

        <form action="" method="POST" class="ps-cc__form" data-convert-form="ETC">
          <h4 class="ps-cc__form-title"><?php _e('Convert ETC to BTC', 'ps-crypto-converter') ?></h4>

          <div class="ps-cc__form-item">
            <input type="number" class="ps-cc__form-input ps-cc__form-input_etc" value="1" data-convert-cur required>
            <div class="ps-cc__form-arrow"></div>
            <input type="text" class="ps-cc__form-input ps-cc__form-input_btc ps-cc__form-input_disabled" value="<?= $btc ?>" disabled data-convert-res>
          </div>
          <button class="ps-cc__form-btn" data-convert-btn><?php _e('Convert', 'ps-crypto-converter') ?></button>
      </div>
      </form>

      <div class="ps-cc__log" data-convert-log>
        <h4 class="ps-cc__log-title"><?php _e('Last recently converted', 'ps-crypto-converter') ?></h4>
        <?php
        $items = get_option('crypto_convert_log'); ?>
        <ol class="ps-cc__log-list" data-convert-list>
          <?php if (!empty($items)) {
            foreach ($items as $it) { ?>
              <li class="ps-cc__log-item"><?= $it ?></li>
            <?php }
          } ?>
        </ol>
      </div>

    </div>
  </div>
  <?php

}