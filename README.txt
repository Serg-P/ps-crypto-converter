=== Simple cryptocurrency converter ===
Contributor: Patlan Sergey
Requires at least: 5.9.2
Tested up to: 5.9.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt

== Description ==

Since it's just a test version here everything is simple. Converting BTC to ETC and ETC to BTC.
Used free key api. In free mode available 333 requests per day.

To update the current course every 5 minutes WP CRON should work.

The last 10 default conversion is displaying, you can also change the number in settings.
You can change the course manually in settings but course is updated every 5min from api https://coinmarketcap.com/
You can also enter your api key in settings.

== Installation ==

1. Upload folder `ps-crypto-converter` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php if (function_exists('ps_crypto_converter')) {ps_crypto_converter();} ?>` in your templates