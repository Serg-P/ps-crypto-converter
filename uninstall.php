<?php
/**
 * If uninstall not called from WordPress, then exit.
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

/**
 * delete settings
 */
delete_option('convert_settings_options');
delete_option('crypto_convert_log');