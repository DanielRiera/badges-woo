=== Badges Woo ===
Contributors: DanielRiera
Donate link: https://www.paypal.com/donate/?hosted_button_id=EZ67DG78KMXWQ
Tags: badges, woocommerce, product badges, sales, conversion
Version: 1.2.1
Requires at least: 5.0
Tested up to: 6.9.4
WC requires at least: 3.0
WC tested up to: 10.6.1
Required WP: 5.0
Tested WP: 6.9.4
Requires PHP: 8.0
Stable tag: 1.2.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Badges Woo lets you display custom badges over WooCommerce product images without editing the original image.

You can create badges manually per product, assign reusable presets, or apply presets automatically when products match specific rules such as sale, featured, out of stock, or new.

The plugin is designed to work on shop loops, category archives, tag archives, and single product pages.

= Main features =

- Manual product badges with custom text
- Automatic badge rules:
  - `sale`
  - `featured`
  - `outofstock`
  - `new`
- Global presets that can be assigned to products
- Automatic global presets that apply when product rules match
- Product badge priority over preset badges
- Optional display on the single product page
- Optional setting to hide the default WooCommerce sale badge when a custom badge exists
- Multiple positions:
  - `top`
  - `bottom`
  - `left`
  - `right`
  - `center`
  - `left-top`
  - `right-top`
  - `left-bottom`
  - `right-bottom`
  - `top-left`
  - `top-right`
  - `bottom-left`
  - `bottom-right`
  - `center-left`
  - `center-right`
- Multiple shapes:
  - `default`
  - `rounded`
  - `pill`
  - `circle`
  - `diamond`
  - `star`
  - `hexagon`
  - `ticket`
  - `bookmark`
  - `burst`
- Adjustable colors, opacity, font size, font weight, and single product zoom
- Emoji support

= Badge priority =

Badge output is resolved in this order:

1. Product badge
2. Selected product preset
3. Global auto-apply preset

If an automatic rule is active and no custom text is defined, the plugin uses the default rule label such as `Sale`, `Featured`, `Out of stock`, or `New`.

== Installation ==

1. Upload the plugin to `/wp-content/plugins/badges-woo/`, or install it from the WordPress admin.
2. Activate the plugin.
3. Edit a WooCommerce product to configure a product badge.
4. Go to `WooCommerce > Badges Woo` to manage presets and global settings.

== Frequently Asked Questions ==

= Can I use emojis in badge text? =

Yes. Badge text supports emojis.

= Can I hide the default WooCommerce sale badge? =

Yes. In `WooCommerce > Badges Woo`, enable:

`Hide default WooCommerce badges when a custom badge exists`

= Does it work on shop and category pages? =

Yes. The plugin injects badges into WooCommerce product image output for shop archives, category pages, tag pages, and single product pages.

= How can I change how many days a product is considered new? =

The automatic `new` rule uses `30` days by default.

Add this snippet to your theme or child theme `functions.php`:

```php
add_filter('woobadges_newness_days', function($days, $product) {
    return 14;
}, 10, 2);
```

Examples:

- `14` means products created in the last 14 days match the `new` rule
- `30` keeps the default behavior
- `60` keeps products marked as new for longer

= Are translations included? =

Yes. Translation files are included in `/languages`, and the plugin uses the `badges-woo` text domain.

== Changelog ==

= 1.2.0 =

- Updated compatibility for WordPress `6.9.4`
- Updated compatibility for WooCommerce `10.6.1`
- Added global presets
- Added preset selection on products
- Added automatic preset rules
- Added more badge positions
- Added visual position selectors
- Added multiple badge shapes
- Added visual shape selectors
- Added setting to hide the default WooCommerce sale badge when a custom badge exists
- Improved admin UI for presets and badge configuration
- Cleaned up translation files and text domain loading

= 1.1.0 =

- Updated compatibility with WordPress 6

= 1.0.9 =

- Updated compatibility with WordPress 5.7.2

= 1.0.8 =

- Fixed error when selecting `none`

= 1.0.6 =

- Fixed Elementor Pro compatibility
- Minor fixes

= 1.0.5 =

- Fixed CSS
- Added emoji link
- Fixed cart images
- Fixed mini-cart images
- Fixed widget images

= 1.0.4 =

- Fixed single product page gallery with Flexslider

= 1.0.3 =

- Fixed CSS with Astra theme
- Added font-weight support

= 1.0.2 =

- Fixed CSS
- Added single product page badge support
- Added zoom on single product page

= 1.0.1 =

- Language fix

= 1.0.0 =

- Initial release
