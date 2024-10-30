<?php
/*
Plugin Name: IP2Currency
Plugin URI: http://www.ip2currency.com
Description: The IP2Currency plugin allows visitor to view the currency value on the your blogsite in their localize currency (homeland currency). There are no programming works involved, but a single shortcode is all you need to produce the result. And, the best part was the result will be automatically determined by our server based on the origin of the visitors.
Version: 1.2.12
Author: IP2Location
Author URI: http://www.ip2currency.com
*/

add_action('admin_menu', ['IP2Currency', 'menu']);
add_filter('the_content', ['IP2Currency', 'parse']);
add_action('admin_enqueue_scripts', ['IP2Currency', 'plugin_enqueues']);
add_action('wp_ajax_ip2currency_submit_feedback', ['IP2Currency', 'submit_feedback']);
add_action('admin_footer_text', ['IP2Currency', 'admin_footer_text']);

class IP2Currency
{
	public function activate()
	{
		$options = ['title' => 'IP2Currency'];

		if (!get_option('IP2Currency')) {
			add_option('IP2Currency', $options);
		} else {
			update_option('IP2Currency', $options);
		}
	}

	public function deactivate()
	{
		delete_option('IP2Currency');
	}

	public function menu()
	{
		add_submenu_page('options-general.php', 'IP2Currency', 'IP2Currency', 'administrator', basename(__FILE__), ['IP2Currency', 'setting']);
	}

	public function setting()
	{
		$currencies = [
			'AED' => 'United Arab Emirates dirham',
			'ANG' => 'Netherlands Antillean guilder',
			'ARS' => 'Argentine peso',
			'AUD' => 'Australian dollar',
			'BRL' => 'Brazilian real',
			'BSD' => 'Bahamian dollar',
			'CAD' => 'Canadian dollar',
			'CHF' => 'Swiss franc',
			'CLP' => 'Chilean peso',
			'CNY' => 'Renminbi',
			'COP' => 'Colombian peso',
			'CZK' => 'Czech koruna',
			'DKK' => 'Danish krone',
			'EUR' => 'Euro',
			'FJD' => 'Fijian dollar',
			'GBP' => 'Pound sterling',
			'GHS' => 'Ghana cedi',
			'GTQ' => 'Guatemalan quetzal',
			'HKD' => 'Hong Kong dollar',
			'HNL' => 'Honduran lempira',
			'HRK' => 'Croatian kuna',
			'HUF' => 'Hungarian forint',
			'IDR' => 'Indonesian rupiah',
			'ILS' => 'Israeli new shekel',
			'INR' => 'Indian Rupee',
			'ISK' => 'Icelandic króna',
			'JMD' => 'Jamaican dollar',
			'JPY' => 'Japanese yen',
			'KRW' => 'South Korean won',
			'LKR' => 'Sri Lankan rupee',
			'MAD' => 'Moroccan dirham',
			'MMK' => 'Burmese kyat',
			'MXN' => 'Mexican peso',
			'MYR' => 'Malaysian ringgit',
			'NOK' => 'Norwegian krone',
			'NZD' => 'New Zealand dollar',
			'PAB' => 'Panamanian balboa',
			'PEN' => 'Peruvian nuevo sol',
			'PHP' => 'Philippine peso',
			'PKR' => 'Pakistani Rupee',
			'PLN' => 'Polish złoty',
			'RON' => 'Romanian leu',
			'RSD' => 'Serbian dinar',
			'RUB' => 'Russian ruble',
			'SEK' => 'Swedish krona',
			'SGD' => 'Singapore dollar',
			'THB' => 'Thai baht',
			'TND' => 'Tunisian dinar',
			'TRY' => 'Turkish lira',
			'TTD' => 'Trinidad and Tobago dollar',
			'TWD' => 'New Taiwan dollar',
			'USD' => 'United States dollar',
			'VEF' => 'Venezuelan bolivar',
			'VND' => 'Vietnamese đông',
			'XAF' => 'Central African CFA franc',
			'XCD' => 'East Caribbean dollar',
			'XPF' => 'CFP franc',
			'ZAR' => 'South African rand',
		];
		$options = get_option('IP2Currency');

		if (!is_array($options)) {
			$options = [
			'licenseKey' => '',
		];
		}

		if (isset($_POST['ip2currency-key'])) {
			if (preg_match('/^[0-9A-Z]{2}-[0-9A-Z]{4}-[0-9A-Z]{4}$/', $_POST['ip2currency-key'])) {
				update_option('IP2Currency', ['licenseKey' => $_POST['ip2currency-key']]);

				$options = get_option('IP2Currency');

				echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div> ';
			}
		}

		echo '
		<style type="text/css">
			.example {color:#675D1C;border: 1px solid #4D75BD;background:#FFFFCC;padding:10px;overflow: visible;}
			.table{table-layout: fixed;font-size:11px;border:1px solid #4D75BD;}
			.table,.table tr td{padding:0;margin:0;}
			.table thead tr{background:#4D75BD;height:25px;}
			.table thead td{padding-left:4px;color:#F3F6F7;font-weight:bold;}
			.table thead td a{padding-left:4px;color:#F3F6F7;font-weight:bold;}
			.table tbody tr{background:none;height:25px;border-bottom:solid 1px #8994A0;}
			.table tbody tr:hover{background:#D8E2F5;}
			.table tbody td{padding-left:4px;}
		</style>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"></div>
			<h2>IP2Currency Settings</h2>
			<p>&nbsp;</p>
			<form id="form-ip2currency" method="post">
			<table>
			<tr>
				<td>API Key</td>
				<td><input style="width:200px;" name="ip2currency-key" type="text" value="' . htmlspecialchars($options['licenseKey'], ENT_QUOTES) . '" /></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>
					<input type="submit" name="submit" class="button-primary" value="Save Changes" />
				</td>
			</tr>
			</table>
			</form>

			<p>&nbsp;</p>

			<b>Usage:</b>
			<p></p>
			<div class="example">
				[ip2currency base="<b>&lt;CURRENCY_CODE&gt;</b>" value="<b>&lt;PRICE_AMOUNT&gt;</b>"]
			</div>
			<p>&nbsp;</p>
			<b>Example:</b>
			<p></p>
			<div class="example">
				<b>Code:</b><br />
				This item is sold at 1000 Yen (~ $[ip2currency base="JPY" value="1000"])

				<br /><br />
				<b>Output for United States visitors:</b><br />
				This item is sold at 1000 Yen (~ $12 USD)

				<br /><br />
				<b>Output for China visitors:</b><br />
				This item is sold at 1000 Yen (~ $76 RMB)
			</div>

			<p>&nbsp;</p>

			<b>Currency Code:</b>
			<table class="table" cellspacing="0">
			<thead>
			<tr>
				<td>Code</td>
				<td>Currency Name</td>
			</tr>
			</thead>
			<tbody>';

		foreach ($currencies as $currencyCode => $currencyName) {
			echo '
				<tr>
					<td>' . $currencyCode . '</td>
					<td>' . $currencyName . '</td>
				</tr>';
		}

		echo '
			</tbody>
			</table>

			<p>&nbsp;</p>

			<p>If you like this plugin, please leave us a <a href="https://wordpress.org/support/view/plugin-reviews/ip2currency">5 stars rating</a>. Thank You!</p>
		</div>';
	}

	public function parse($content)
	{
		//to replace other quotes to normal quotation marks
		$content = str_replace('&#8220;', '"', $content);
		$content = str_replace('&#8221;', '"', $content);
		$content = str_replace('&#8243;', '"', $content);

		if (preg_match_all('/(\[ip2currency base="([^"]+)" value="([^"]+)"\])/is', $content, $matches)) {
			$options = get_option('IP2Currency');

			$soap = new SoapClient('http://v1.fraudlabs.com/ip2currencywebservice.asmx?wsdl');

			$i = 0;

			foreach ($matches[1] as $tag) {
				$result = $soap->IP2Currency(['VISITORIP' => $_SERVER['REMOTE_ADDR'], 'FROMCURRENCYCODE' => $matches[2][$i], 'FROMAMOUNT' => $matches[3][$i++], 'LICENSE' => $options['licenseKey']]);

				if ($result) {
					$content = str_replace($tag, $result->TOCURRENCYSYMBOL . ' ' . $result->TOAMOUNT, $content);
				}
			}
		}

		return $content;
	}

	public function plugin_enqueues($hook)
	{
		if ($hook == 'plugins.php') {
			// Add in required libraries for feedback modal
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style('wp-jquery-ui-dialog');

			wp_enqueue_script('ip2currency_admin_script', plugins_url('/assets/js/feedback.js', __FILE__), ['jquery'], null, true);
		}
	}

	public function admin_footer_text($footer_text)
	{
		$plugin_name = substr(basename(__FILE__), 0, strpos(basename(__FILE__), '.'));
		$current_screen = get_current_screen();

		if (($current_screen && strpos($current_screen->id, $plugin_name) !== false)) {
			$footer_text .= sprintf(
				__('Enjoyed %1$s? Please leave us a %2$s rating. A huge thanks in advance!', $plugin_name),
				'<strong>' . __('IP2Currency', $plugin_name) . '</strong>',
				'<a href="https://wordpress.org/support/plugin/' . $plugin_name . '/reviews/?filter=5/#new-post" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
			);
		}

		if ($current_screen->id == 'plugins') {
			return $footer_text . '
			<div id="ip2currency-feedback-modal" class="hidden" style="max-width:800px">
				<span id="ip2currency-feedback-response"></span>
				<p>
					<strong>Would you mind sharing with us the reason to deactivate the plugin?</strong>
				</p>
				<p>
					<label>
						<input type="radio" name="ip2currency-feedback" value="1"> I no longer need the plugin
					</label>
				</p>
				<p>
					<label>
						<input type="radio" name="ip2currency-feedback" value="2"> I couldn\'t get the plugin to work
					</label>
				</p>
				<p>
					<label>
						<input type="radio" name="ip2currency-feedback" value="3"> The plugin doesn\'t meet my requirements
					</label>
				</p>
				<p>
					<label>
						<input type="radio" name="ip2currency-feedback" value="4"> Other concerns
						<br><br>
						<textarea id="ip2currency-feedback-other" style="display:none;width:100%"></textarea>
					</label>
				</p>
				<p>
					<div style="float:left">
						<input type="button" id="ip2currency-submit-feedback-button" class="button button-danger" value="Submit & Deactivate" />
					</div>
					<div style="float:right">
						<a href="#">Skip & Deactivate</a>
					</div>
				</p>
			</div>';
		}

		return $footer_text;
	}

	public function submit_feedback()
	{
		$feedback = (isset($_POST['feedback'])) ? $_POST['feedback'] : '';
		$others = (isset($_POST['others'])) ? $_POST['others'] : '';

		$options = [
			1 => 'I no longer need the plugin',
			2 => 'I couldn\'t get the plugin to work',
			3 => 'The plugin doesn\'t meet my requirements',
			4 => 'Other concerns' . (($others) ? (' - ' . $others) : ''),
		];

		if (isset($options[$feedback])) {
			if (!class_exists('WP_Http')) {
				include_once ABSPATH . WPINC . '/class-http.php';
			}

			$request = new WP_Http();
			$response = $request->request('https://www.ip2location.com/wp-plugin-feedback?' . http_build_query([
				'name'    => 'ip2currency',
				'message' => $options[$feedback],
			]), ['timeout' => 5]);
		}
	}
}
