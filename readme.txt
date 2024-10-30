=== Plugin Name ===
Contributors: IP2Location
Tags: ip2currency, ip2location
Requires at least: 2.0.0
Tested up to: 6.6
Stable tag: 1.2.12

Display price in visitor's origin currency.

== Description ==

The IP2Currency plugin allows visitor to view the currency value on the your blogsite in their localize currency (homeland currency). There are no programming works involved, but a single shortcode is all you need to produce the result. And, the best part was the result will be automatically determined by our server based on the origin of the visitors.

Why I need this? Assuming that you are writing a traveling blog with food priced in SGD, a visitor from United States may find it difficult to estimate the perceived amount in USD while reading your blog. Unless they check the exchange rate from website and make the conversion themselves. Hence, wouldn't be nice if you can have a special note displaying the value in their local currency? For example,

Noodle S$5.00 (approx. $3.90)
Satay S$10.00 (approx. $7.90)

As mentioned above, it requires no programming to get it work. You just need to insert the shortcode as followed inside your POST.

Noodle S$5.00 (approx. [ip2currency base="USD" value="5.00"])
Satay S$10.00 (approx. [ip2currency base="USD" value="10.00"])


Furthermore, the result is dynamic. A visitor from China will see the value in CNY, while the visitor from Canada will see the value in CAD. No hassle from you.


Key Features:
- Automatically determine the origin of visitors by using the geo technology (powered by IP2Location.com)
- Automatically determine the commonly used local currency.
- Autmatically return converted amount using the latest exchange rate.
- The exchange rate was getting from a reliable data source and was daily updated.
- Ability to support multiple base currency.
- Displayed the currency symbol of the converted value.

For more information, please visit http://www.ip2currency.com

Note: This plugin existed to support old users only. No new API keys will be available for sign up.

== Installation ==

*Using the WordPress dashboard*

1. Login to your wordpress
1. Go to Plugins
1. Select Add New
1. Search for IP2Currency
1. Select Install
1. Select Install Now
1. Select Activate Plugin

*Manual*

1. Download and unzip the plugin
1. Upload the entire *IP2Currency/* directory to the */wp-content/plugins/* directory
1. Activate the plugin through the Plugins menu in WordPress

*How to Use*

1. Go to Settings => IP2Currency, as shown in the screenshot below.
1. The setting page provides you guideline on how to use this plugin.
1. Enter the API Key and save the changes.
1. You are ready to use the code inside your post.

== Frequently Asked Questions ==

= About IP2Currency =

For more information, please visit http://www.ip2currency.com.

== Screenshots ==

1. Basic interface
1. Insert license key