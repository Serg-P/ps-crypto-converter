<?php
/**
 * Plugin Name:       Simple cryptocurrency converter
 * Plugin URI:        https://www.upwork.com/freelancers/~01e0cee19e9c93fe8e
 * Description:       Plugin converting the cryptocurrency BTC to ETC.
 * Version:           1.0.0
 * Author:            Sergey Patlan
 * Author URI:        https://www.upwork.com/freelancers/~01e0cee19e9c93fe8e
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ps-crypto-converter
 * Domain Path:       /languages
 * Requires at least: 5.9.2
 * Requires PHP:      7.4
 */

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
  die;
}

/**
 * Include files
 */
require_once plugin_dir_path(__FILE__) . 'class-crypto-converter.php';
require_once plugin_dir_path(__FILE__) . 'includes/crypto-converter-ajax.php';

/**
 * If exist class
 */
if (class_exists('Crypto_Convert')) {
  /**
   * Init
   */
  $psCryptoMain = new Crypto_Convert();

  /**
   * Register hooks
   */
  register_activation_hook(__FILE__, [$psCryptoMain, 'activate']);
  register_activation_hook(__FILE__, [$psCryptoMain, 'get_api']);
  register_deactivation_hook(__FILE__, [$psCryptoMain, 'deactivate']);
}