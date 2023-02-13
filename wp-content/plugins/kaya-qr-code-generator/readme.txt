=== Kaya QR Code Generator ===

Contributors: kayastudio
Donate link: http://dotkaya.org/a-propos/
Tags: QR Code, qrcode, Widget, Shortcode, WooCommerce, QR Code Widget, QR Code Shortcode
Tested up to: 6.0
Stable tag: trunk
Text Domain: kaya-qr-code-generator
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate QR Code through Widgets and Shortcodes, without any dependencies.

== Description ==

**Why use "Kaya QR Code Generator"?**

This plugin creates QR Codes (Quick Response codes) through a widget or a shortCode for easy insertion into your pages, posts, sidebars, WooCommerce products, etc.

Easy install and use, generate dynamic QR Codes with your custom settings. Content can be any text, link and even a Bitcoin address or the current page URL.

The QR Code generator library is included (based on qr.js written by Kang Seonghoon) and don't need any dependencies.

= Features =

* Use static or dynamic content.
* Use as built-in Widget.
* Use as shortcode with generator assistant.
* Clickable link on image.
* Image display settings.
* Color and background color customizable.
* QR Code preview and download on Shortcode generator assistant.
* The Shortcode generator assistant is available on pages, posts, WooCommerce products, any public custom post types and on the plugin option page.
* Compatible with WordPress MultiSite and WooCommerce.

= Shortcodes =

* Basic shortcode for a static content: `[kaya_qrcode content="my encoded content"]`.
* Basic shortcode for a dynamic content: `[kaya_qrcode_dynamic][example_shortcode][/kaya_qrcode_dynamic]`.

= Available Languages =

* English.
* French.

= Feedback =

Any suggestions or feedback is welcome, thank you for using or trying one of my plugins. Please take the time to let me know about your experiences and rate this plugin.

== Screenshots ==

1. Kaya QR Code Generator: Widget.
2. Kaya QR Code Generator: Shortcode generator assistant.
3. Kaya QR Code Generator: Shortcode generator assistant with dynamic content.
4. Kaya QR Code Generator: Custom QR Code display examples.

== Frequently Asked Questions ==

= What if I wish to modify the QR Code size or error correction level? =

No problem, Kaya QR Code Generator is fully customizable.

You can modify the size in pixels and the error correction level (Low ~7%, Medium ~15%, Quarter ~25% and High ~30%).

= What if I wish to custom the QR Code display? =

No problem, Kaya QR Code Generator is fully customizable.

You can add a title, shadows on image, modify the horizontal alignment and use custom colors and background colors.

= What if I wish to add a link on my QR Code? =

No problem, Kaya QR Code Generator is fully customizable.

You can add a destination URL, and make it open in a new window.

= How to find and use shortcode generator assistant? =

No problem, Kaya QR Code Generator is easy to use.

If you want to display the qr-code by a widget, the generator is used by default.

If you want to display the qr-code in a page, a post, a WooCommerce product or in any public custom post type, the generator is under the administration primary content of your page / post / product.

If the ‘Kaya QR Code Generator’ panel is not displayed, verify that ‘Kaya QR Code Generator’ is checked in the page / post / product options, in ‘Show more tools & options’ > ‘Options’ and ‘Advanced Panels’.

The shortcode generator assistant is also available in the plugin options page.

= How to use dynamic content? =

No problem, Kaya QR Code Generator is easy to use with dynamic content (other shortcodes).

If you want to display dynamic content by a widget, the generator is used by default and you just need to check the checkbox "Use dynamic content (other shortcodes)".

If you want to display dynamic content by a shortcode, use this following shortcode: `[kaya_qrcode_dynamic][example_shortcode][/kaya_qrcode_dynamic]`.

= Why my modifications are not saved when I update my post? =

The ‘Kaya QR Code Generator’ panel available in a page, a post, a WooCommerce product or in any public custom post type, is not used as custom fields for the post and don’t affect anything in the page content.

The generated shortcode must be pasted in a “shortcode block” or directly in the page content.

= Can I use the shortcodes in a PDF or a mail? =

No, the QR Code shortcode must be present in a WordPress page, because it uses JavaScript functions to generate the image of the QR Code.

But you can download the generated image on Shortcode generator assistant for example and use it as you want.

= How to support the advancement of this plugin? =

Any suggestions or feedback is welcome, please take the time to let me know about your experiences and rate this plugin.

You can help to support the advancement by donate to this plugin, see more details on http://dotkaya.org/a-propos/

== Installation ==

The quickest way:

1. Go to the Plugins Menu in WordPress and select Plugins > Add new
1. Search for "Kaya QR Code Generator"
1. Click "Install" and "activate".

The other way:

1. Upload the "kaya-qr-code-generator" folder to the "/wp-content/plugins/" directory
1. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 1.4.2 =
* Fix: Uncaught error of class not found when executed outside administration like cron daemon.
* Fix: Alignment problem with the link surrounding the image.
* Adding: Title alignment feature.

= 1.4.1 =
* Adding: Plugin options page in dashboard with shortcode generator assistant.
* Adding: Important notice in the post's shortcode generator assistant.

= 1.4.0 =
* Fix: Illegal string offset warning when dynamic shortcode content is empty.
* Fix: Check for illegal characters in size and colors parameters.
* Fix: Allow Hexadecimal ASCII codes in the content.
* Adding: Anchor link parameter added to the automatic current page url.
* Adding: QR Code image clickable link available with the automatic current page url.

= 1.3.2 =
* Fix: htaccess options modified to prevent errors on some Apache configuration.

= 1.3.1 =
* Fix: Undefined wpkqcg_qrcode_display cache error with execution on DOMContentLoaded.

= 1.3.0 =
* Fix: Undefined variable qrcodeCssInlineBasic Notice.
* Adding: Shortcode generator assistant available in any public custom post types.
* Adding: Management of dynamic content using other shortcodes, via new shortcode [kaya_qrcode_dynamic][/kaya_qrcode_dynamic].

= 1.2.0 =
* Adding: QR Code preview and download on Shortcode generator assistant.
* Adding: Shortcode generator assistant available in WooCommerce products page.
* Adding: Better management of admin scripts.
* Adding: Lighter loading of scripts (JS compressed).
* Fix: Full width display bug of image.

= 1.1.1 =
* Adding: Better management and lighter loading of scripts and resources.

= 1.1.0 =
* Adding: Custom color and background color support.
* Adding: Allow custom image alternate text.

= 1.0.2 =
* Adding: Allow "bitcoin:" as Bitcoin URI scheme.
* Adding: Better management for resized image.

= 1.0.1 =
* Adding: noopener and noreferrer to links with target="_blank".
* Adding: French translation.

= 1.0.0 =
* KQCG is ready for wordpress.org.

= 0.1.0 =
* "Kaya QR Code Generator" is created.