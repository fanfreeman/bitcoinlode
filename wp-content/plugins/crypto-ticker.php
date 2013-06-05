<?php

   /*
   Plugin Name: Cryptocurrency Ticker
   Description: Fetches, caches, and displays current cryptocurrency prices (bitcoin, litecoin, etc).
   Version: 1.0
   Author: Bill Kennedy
   License: GPL2
   */

define('CACHE_FILENAME', 'crypto-ticker-cache.html');

class CryptoTickerWidget extends WP_Widget
{
  function CryptoTickerWidget()
  {
    $widget_ops = array('classname' => 'CryptoTickerWidget', 'description' => 'Displays current cryptocurrency prices.' );
    $this->WP_Widget('CryptoTickerWidget', 'Cryptocurrency Ticker', $widget_ops);
  }
 
  function form($instance)
  {
		$defaults = array( 'title' => 'Ticker', 'delete_cache' => 0, 'cache' => 2, 'show_btc' => 1, 'show_ltc' => 1 );
		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'cache' ); ?>">Cache Time (minutes, 1-120):</label>
				<input id="<?php echo $this->get_field_id( 'cache' ); ?>" name="<?php echo $this->get_field_name( 'cache' ); ?>" value="<?php echo $instance['cache']; ?>" style="width:100%;" />
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_btc'], 1 ); ?> id="<?php echo $this->get_field_id( 'show_btc' ); ?>" name="<?php echo $this->get_field_name( 'show_btc' ); ?>" value="1" /> 
				<label for="<?php echo $this->get_field_id( 'show_btc' ); ?>">Show Bitcoin quote?</label>
			</p>
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['show_ltc'], 1 ); ?> id="<?php echo $this->get_field_id( 'show_ltc' ); ?>" name="<?php echo $this->get_field_name( 'show_ltc' ); ?>" value="1" /> 
				<label for="<?php echo $this->get_field_id( 'show_ltc' ); ?>">Show Litecoin quote?</label>
			</p>
			
			
			<p>
				<input class="checkbox" type="checkbox" <?php checked( $instance['delete_cache'], 1 ); ?> id="<?php echo $this->get_field_id( 'delete_cache' ); ?>" name="<?php echo $this->get_field_name( 'delete_cache' ); ?>" value="1" /> 
				<label for="<?php echo $this->get_field_id( 'delete_cache' ); ?>">Delete cache?</label>
				<div style="font-size:smaller;">(Delete the cache to force any new changes you make to take effect immediately.)</div>
			</p>
		<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    if (is_numeric($new_instance['cache']) and $new_instance['cache'] >= 1 and $new_instance['cache'] <= 120) {
    	$instance['cache'] = $new_instance['cache'];
  	}
    $instance['show_btc'] = $new_instance['show_btc'];
    $instance['show_ltc'] = $new_instance['show_ltc'];
    if ($new_instance['delete_cache'] == 1) {
    	$this->deleteCache();
    }
    return $instance;
  }
  
  function deleteCache() {
		$cachefile = plugin_dir_path( __FILE__ ).CACHE_FILENAME;
		
		if (file_exists($cachefile)) {
			unlink($cachefile);
		} 
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;

		$cache = $instance['cache'] * 60;
		$show_btc = $instance['show_btc'];
		$show_ltc = $instance['show_ltc'];

    $this->renderTickers($cache, $show_btc, $show_ltc);
    
    echo $after_widget;
  }
  
  // draws the actual ticker prices
  function renderTickers($cachetime, $btc, $ltc) 
  {
		$cachefile = plugin_dir_path( __FILE__ ).CACHE_FILENAME;
		
		// Serve from the cache if it is younger than $cachetime
		if (file_exists($cachefile) && time() - $cachetime < filemtime($cachefile)) {
		    echo "<!-- Cached ticker, generated ".date('H:i', filemtime($cachefile))." -->\n";
		    include($cachefile);
		} 
		else 
		{
			ob_start(); // Start the output buffer
			
			// start ticker tables
			?>
			<table class="crypto-ticker-tbl"><tr><td><table class="crypto-ticker-tbl">
			<?php
			
			// display each ticker quote
			if ($btc == 1) {
				$mtgox_json = $this->get_data('https://data.mtgox.com/api/2/BTCUSD/money/ticker');
				$mtgox_decoded = json_decode($mtgox_json, true);
				$this->displayTickerLine('Bitcoin', 'BTC', $mtgox_decoded['data']['last_all']['display'], $mtgox_decoded['data']['last_all']['currency'], 'Mt. Gox', 'http://www.mtgox.com');
			}
			
			if ($ltc == 1) {
				$btce_json = $this->get_data('https://btc-e.com/api/2/ltc_usd/ticker');
				$btce_decoded = json_decode($btce_json, true);
				$this->displayTickerLine('Litecoin', 'LTC', '$'.number_format(round($btce_decoded['ticker']['last'], 2), 2, '.', ''), 'USD', 'BTC-e', 'http://www.btc-e.com');
			}
			
			// end tables & show quote disclaimer
			?>
			</table></td></tr><tr>
				<?php if ($cachetime > 0) { ?>
				<td class="crypto-ticker-delay">Quotes delayed up to <?php echo ($cachetime / 60); ?> minute<?php if ($cachetime >= 120) { echo 's'; } ?>.</td>
				<?php } ?>
			</tr></table>
			<?php
			
			// Cache the contents to a file
			$cached = fopen($cachefile, 'w');
			fwrite($cached, ob_get_contents());
			fclose($cached);
			ob_end_flush(); // Send the output to the browser
		}
  }
  
  function displayTickerLine($name, $abbrev, $quote, $currency, $exchangeName, $exchangeUrl)
  {
		?>
		<tr><td class="crypto-ticker-cell-icon">
				<img src="<?php echo plugins_url( 'img/'.$abbrev.'.png' , __FILE__ ); ?>" title="<?php echo $name; ?>" class="crypto-ticker-icon" />
			</td>
			<td class="crypto-ticker-cell-abbrev">
				1 <?php echo $abbrev; ?> = 
			</td>
			<td class="crypto-ticker-cell-quote">
				<font style="font-weight:bold;"><?php echo $quote; ?></font> <?php echo $currency; ?>
			</td>
			<td class="crypto-ticker-cell-exch">
				&nbsp;(via <a href="<?php echo $exchangeUrl; ?>" target="_blank"><?php echo $exchangeName; ?></a>)
		</td></tr>
		<?php
  }
  
	/* gets data from a URL */
	function get_data($url) 
	{
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
 
}

function prefix_add_style() {
	wp_register_style( 'crypto-ticker-style', plugins_url('css/crypto-ticker.css', __FILE__) );
	wp_enqueue_style( 'crypto-ticker-style' );
}

add_action( 'wp_enqueue_scripts', 'prefix_add_style' );
add_action( 'widgets_init', create_function('', 'return register_widget("CryptoTickerWidget");') );

?>