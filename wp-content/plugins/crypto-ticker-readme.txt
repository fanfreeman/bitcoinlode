=== Cryptocurrency Ticker ===
Contributors: rbbrdckybk
Tags: bitcoin, ticker, litecoin, cryptocurrency, quote, price
Requires at least: 2.8
Tested up to: 3.5.1
Stable tag: 1.01
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Fetches, caches, and displays current cryptocurrency prices (bitcoin and litecoin, for now).

== Description ==

Cryptocurrency Ticker displays current cryptocurrency prices (bitcoin and litecoin) on your WordPress site. You can choose to display a current bitcoin and/or litecoin price.

Prices are fetched from mtgox.com (bitcoin) and btc-e.com (litecoin) using their respective APIs.

Ticker prices are cached for a duration that you specify in the widget menu, to improve performance and prevent your site from making a ton of requests to mtgox.com and btc-e.com.

== Installation ==

1. Upload the contents of the plugin .zip to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In the 'Widgets' menu in WordPress, place the widget where you want it to appear on your site
4. Verify that the plugin's settings are what you want in the widget menu

== Frequently Asked Questions ==

= Can I change the look of the widget? =

Yes, simply modify the included crypto-ticker.css file, located in the plugin's /css directory.

You can preview the look of the plugin on http://www.cryptobadger.com . 

= What about support for other cryptocurrencies? =

I may add the smaller altcoins in the future if there is enough interest. If you can't wait, adding them yourself should be a trivial task; just keep in mind that because of the way the btc-e API is set up, each additional currency means an additional request, which will impact performance on cache misses. If I do decide to add the other altcoins, I'll probably re-write the widget so that the requests are asynchronous.

= Can the ticker display prices in currencies other than USD? =

Not at the moment. If there is enough interest I'll add other currencies later.

= Help! The ticker doesn't seem to be fetching prices! =

Try temporarily disabling any other Wordpress caching plugins that you're running, and then delete my plugin's cache from the options menu. If that doesn't work, ensure that outbound traffic on port 443 (SSL/HTTPS) is open from your server - the plugin fetches quotes on that port.

== Changelog ==

= 1.01 =
* Fixed Mt. Gox price fetch - they updated their API to allow only secure (HTTPS) requests.

= 1.0 =
* Initial release.

== Donations ==

If this plugin helped you, feel free to tip me some beer money. =)

* BTC:  1Gryw6P4xDgpysdtPbBDYN8Vz78uCSmN2K 
* LTC:  LQGpAD5eQPXbR2NpHDzj77LBq7oQgeakqA 


