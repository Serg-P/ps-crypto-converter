<?php

/**
 * admin ajax url
 */
add_action('wp_enqueue_scripts', 'ajaxurl_data', 99);
function ajaxurl_data ()
{
  wp_localize_script('psCryptoConvertPubScript', 'ps_crypto_ajax_url',
    array (
      'url' => admin_url('admin-ajax.php')
    )
  );
}

/**
 * convert currency
 */
function psCryptoConvert ()
{
  $value    = !empty($_POST['value']) ? $_POST['value'] : false;
  $currency = !empty($_POST['currency']) ? $_POST['currency'] : false;

  if (!$value || !$currency) die();

  $options = get_option('convert_settings_options');
  $etc     = !empty($options['ETC']) ? round($options['ETC'], 4) : '';
  $btc     = $etc ? round(1 / $etc, 7) : '';

  $my_log = get_option('crypto_convert_log');
  $count_set = !empty($options['posts_per_page'])? $options['posts_per_page'] : 10;
  if (empty($my_log)) $my_log = [];


  if ($currency === "BTC") {
    array_unshift($my_log, "$value BTC " . __('to', 'ps-crypto-converter') . " ETC");
    echo $value * $etc;
  }
  else {
    array_unshift($my_log, "$value ETC " . __('to', 'ps-crypto-converter') . " BTC");
    echo $value * $btc;
  }

  if(count($my_log) > $count_set){
    array_splice($my_log, $count_set);
  }
  update_option('crypto_convert_log', $my_log);

  die();
}

add_action('wp_ajax_psCryptoConvert', 'psCryptoConvert');
add_action('wp_ajax_nopriv_psCryptoConvert', 'psCryptoConvert');

/**
 * get log
 */
function psCryptoConvertLog ()
{
  $items = get_option('crypto_convert_log');
  ?>
  <?php if (!empty($items)) {
  foreach ($items as $it) { ?>
    <li class="ps-cc__log-item"><?= $it ?></li>
  <?php }
}
  die();
}

add_action('wp_ajax_psCryptoConvertLog', 'psCryptoConvertLog');
add_action('wp_ajax_nopriv_psCryptoConvertLog', 'psCryptoConvertLog');
