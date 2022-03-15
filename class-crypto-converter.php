<?php
/**
 * Main Class
 */

class Crypto_Convert
{

  public function __construct ()
  {
    /**
     * enqueue admin script and style
     * create admin page
     * add settings page
     */
    add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
    add_action('admin_menu', [$this, 'admin_page']);

    /**
     * enqueue front script and style
     */
    add_action('wp_enqueue_scripts', [$this, 'enqueue_public']);

    /**
     * settings
     */
    add_filter('plugin_action_links_' . 'ps-crypto-converter/ps-crypto-converter.php', [$this, 'settings_link']);
    add_action('admin_init', [$this, 'settings_init']);

    /**
     * register 5 minute interval
     */
    add_filter('cron_schedules', 'cron_add_five_min');
    function cron_add_five_min ($schedules)
    {
      $schedules['five_min'] = array (
        'interval' => 60 * 5,
        'display'  => __('Update ETC every 5 minutes', 'ps-crypto-converter')
      );
      return $schedules;
    }

    /**
     * register cron event
     */
    add_action('wp', 'ps_crypto_cron_activation');
    function ps_crypto_cron_activation ()
    {
      if (!wp_next_scheduled('ps_crypto_converter_api')) {
        wp_schedule_event(time(), 'five_min', 'ps_crypto_converter_api');
      }
    }

    /**
     * add a function to the specified hook
     */
    add_action('ps_crypto_converter_api', [$this, 'get_api']);

    /**
     * localization
     */
    add_action('plugins_loaded', 'true_load_plugin_textdomain');
    function true_load_plugin_textdomain ()
    {
      load_plugin_textdomain('ps-crypto-converter', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

  }

  /**
   * activate
   */
  static function activate ()
  {
    //update rewrite rules
    flush_rewrite_rules();
  }

  /**
   * deactivate
   */
  static function deactivate ()
  {
    //update rewrite rules
    flush_rewrite_rules();
    wp_clear_scheduled_hook('ps_crypto_converter_api');
  }

  /**
   * enqueue admin
   */
  public function enqueue_admin ()
  {
    wp_enqueue_style('psCryptoConvertStyle', plugins_url('admin/css/main.css', __FILE__));
  }

  /**
   * enqueue public
   */
  public function enqueue_public ()
  {
    wp_enqueue_style('psCryptoConvertPubStyle', plugins_url('public/css/main.css', __FILE__));
    wp_enqueue_script('psCryptoConvertPubScript', plugins_url('public/js/main.js', __FILE__));
    include_once(plugin_dir_path(__FILE__) . 'public/ps-crypto-converter-public.php');
  }

  /**
   * admin page
   */
  public function admin_page ()
  {
    add_menu_page(
      __('Cryptocurrency converter settings page', 'ps-crypto-converter'),
      __('Crypto-converter', 'ps-crypto-converter'),
      'manage_options',
      'crypto_convert_settings',
      [$this, 'settings_page'],
      'dashicons-money-alt',
      4);
  }

  /**
   * include page settings
   */
  public function settings_page ()
  {
    include_once(plugin_dir_path(__FILE__) . 'admin/ps-crypto-converter-admin.php');
  }

  /**
   * settings link
   */
  public function settings_link ($link)
  {
    $settings_link = '<a href="admin.php?page=crypto_convert_settings">' . __('Settings', 'ps-crypto-converter') . '</a>';
    array_push($link, $settings_link);
    return $link;
  }

  /**
   * settings init
   */
  public function settings_init ()
  {
    register_setting('convert_settings', 'convert_settings_options');

    add_settings_section('convert_settings_section', '', [$this, 'settings_section_html'], 'crypto_convert_settings');

    add_settings_field('posts_per_page', __('Store the history log of convert requests. Default 10', 'ps-crypto-converter'), [$this, 'posts_per_page_html'], 'crypto_convert_settings', 'convert_settings_section');
    add_settings_field('ETC', __('1 BTC =', 'ps-crypto-converter'), [$this, 'ETC_html'], 'crypto_convert_settings', 'convert_settings_section');
    add_settings_field('api_key', __('API KEY', 'ps-crypto-converter'), [$this, 'api_key_html'], 'crypto_convert_settings', 'convert_settings_section');
  }

  public function settings_section_html ()
  { ?>
    <h3><?php _e("Since it's just a test version here everything is simple. Used free key api. In free mode available 333 requests per day. For updates ETC every 5 minutes WP CRON must be work", 'ps-crypto-converter') ?></h3>

    <h4 class="ps-crypto-convert__title">
      <?php _e('For display insert in your template this php code', 'ps-crypto-converter') ?>
    </h4>
    <pre>if (function_exists('ps_crypto_converter')) {ps_crypto_converter();}</pre>
  <?php }

  public function posts_per_page_html ()
  {
    $options = get_option('convert_settings_options')
    ?>
    <input type="number" name="convert_settings_options[posts_per_page]" value="<?= !empty($options['posts_per_page']) ? $options['posts_per_page'] : '10' ?>" min="1" max="100">
    <?php
  }

  public function ETC_html ()
  {
    $options = get_option('convert_settings_options')
    ?>
    <p><strong><?= !empty($options['ETC']) ? $options['ETC'] : '' ?>&nbsp<?php _e('ETC', 'ps-crypto-converter') ?></strong></p>
    <p style="margin: 10px 0;"><?php _e('If you want to change the course manually you can use the field. ', 'ps-crypto-converter') ?></p>
    <input type="number" name="convert_settings_options[ETC]" value="<?= !empty($options['ETC']) ? $options['ETC'] : '' ?>">
    <p style="margin-top: 10px;">
      <span class="ps-crypto-convert__note"><?php _e('NOTE !!!', 'ps-crypto-converter') ?></span>
      <?php _e('The course is updated every 5min from api', 'ps-crypto-converter') ?>
      <a href="https://coinmarketcap.com/">https://coinmarketcap.com/</a>
    </p>
    <?php
  }

  public function api_key_html ()
  {
    $options = get_option('convert_settings_options')
    ?>
    <p style="margin:0 0 10px;"><?php _e("If you don't have api key you may get it here", 'ps-crypto-converter') ?>
      <a href="https://pro.coinmarketcap.com/signup/">https://pro.coinmarketcap.com/signup/</a>
    </p>
    <input class="ps-crypto-convert__input" type="text" name="convert_settings_options[api_key]" value="<?= !empty($options['api_key']) ? $options['api_key'] : '40799ff1-0e58-4db0-b23f-0e54b19805ef' ?>">
    <?php
  }


  /**
   * api
   */
  public function get_api ()
  {
    $url        = 'https://pro-api.coinmarketcap.com/v2/tools/price-conversion';
    $parameters = [
      'amount'  => '1',
      'symbol'  => 'BTC',
      'convert' => 'ETC',
    ];

    $my_options = get_option('convert_settings_options');
    $key        = !empty($my_options['api_key']) ? $my_options['api_key'] : '40799ff1-0e58-4db0-b23f-0e54b19805ef';
    $headers    = [
      'Accepts: application/json',
      'X-CMC_PRO_API_KEY:' . $key
    ];

    $qs      = http_build_query($parameters);
    $request = "{$url}?{$qs}";
    $curl    = curl_init();
    curl_setopt_array($curl, array (
      CURLOPT_URL            => $request,
      CURLOPT_HTTPHEADER     => $headers,
      CURLOPT_RETURNTRANSFER => 1
    ));

    $response = curl_exec($curl); // Send the request, save the response
    $result   = json_decode($response);

    if (!empty($result->data)) {
      $result = $result->data[0]->quote;
      if (!empty($result->ETC->price)) {
        $my_options        = get_option('convert_settings_options');
        $my_options['ETC'] = $result->ETC->price;
        update_option('convert_settings_options', $my_options);
      }
    }
    curl_close($curl);
  }

}