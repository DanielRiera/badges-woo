<?php
/**
 * Plugin Name: Badges Woo
 * Description: Show badges for each product on your store
 * Version: 1.2.2
 * Author: Daniel Riera
 * Author URI: https://danielriera.net
 * Text Domain: badges-woo
 * Domain Path: /languages
 * WC requires at least: 3.0
 * WC tested up to: 10.6.1
 * Requires at least: 5.0
 * Requires PHP: 8.0
 * Required WP: 5.0
 * Tested WP: 7.1.3
 */
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('plugins_loaded', function() {
    if(!class_exists('WooCommerce')) { return; }
    define('WOOBADGES_URL', plugin_dir_url( __FILE__ ));
    define('WOOBADGES_PATH', plugin_dir_path( __FILE__ ));
    define('WOOBADGES_VERSION', '1.2.2');
    if(!class_exists('WOOBADGES')) {
        class WOOBADGES {
    
            static $positions = array('none', 'center', 'top', 'left', 'bottom', 'right', 'left-top', 'right-top', 'left-bottom', 'right-bottom', 'top-left', 'top-right', 'bottom-left', 'bottom-right', 'center-left', 'center-right');
            static $automatic_rules = array('none', 'sale', 'featured', 'outofstock', 'new');
            static $shapes = array('default', 'rounded', 'pill', 'circle', 'diamond', 'star', 'hexagon', 'ticket', 'bookmark', 'burst');
            
            public $featuredImage = false;

            function __construct(){
                add_action( 'plugins_loaded', array($this, 'load_text_domain') );
                add_filter('woocommerce_product_get_image', array($this, 'get_image'), 99, 6);
                add_filter('woocommerce_get_product_thumbnail', array($this, 'get_loop_image'), 99, 1);
                add_filter('post_thumbnail_html', array($this, 'filter_post_thumbnail_html'), 99, 5);
                add_filter('woocommerce_sale_flash', array($this, 'maybe_hide_default_sale_flash'), 99, 3);
                add_filter('woocommerce_single_product_image_thumbnail_html', array($this, 'show_on_single_product'), 99, 2);
                add_filter('woocommerce_single_product_image_gallery_classes', array($this, 'show_single_product_badges'), 99, 2);
                
                add_action( 'wp_enqueue_scripts', array($this, 'load_styles') );
                add_action( 'admin_enqueue_scripts', array($this, 'load_script_admin') );
                add_action('save_post_product', array($this, 'save_badges_product'));
                add_action( 'add_meta_boxes', array($this, 'metabox_product_badge') );
                add_action( 'admin_menu', array($this, 'register_settings_page') );
                add_action( 'admin_init', array($this, 'register_settings') );

                add_action( 'before_woocommerce_init', function() {
                    if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
                    }
                } );
            }

            function load_text_domain() {
                $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
                $mofile = WOOBADGES_PATH . 'languages/badges-woo-' . $locale . '.mo';

                unload_textdomain('badges-woo');

                if(file_exists($mofile)) {
                    load_textdomain('badges-woo', $mofile);
                }

                load_plugin_textdomain( 'badges-woo', false, dirname( plugin_basename(__FILE__) ) . '/languages' );
            } 
    
            static function get_positions() {
                $positions = apply_filters('woobadges_positions', self::$positions);
    
                return $positions;
            }

            static function get_automatic_rules() {
                $automatic_rules = apply_filters('woobadges_automatic_rules', self::$automatic_rules);

                return $automatic_rules;
            }

            static function get_shapes() {
                $shapes = apply_filters('woobadges_shapes', self::$shapes);

                return $shapes;
            }

            static function get_default_badge_data() {
                return array(
                    'position' => 'none',
                    'opacity' => '1',
                    'text' => '',
                    'background' => '#333333',
                    'color' => '#FFFFFF',
                    'fontSize' => '12px',
                    'zoomSingleProduct' => '1',
                    'fontWeight' => 'normal',
                    'shape' => 'default',
                    'source' => 'manual',
                    'automaticRule' => 'none'
                );
            }

            static function get_presets() {
                $presets = get_option('woobadges_presets', array());

                return is_array($presets) ? $presets : array();
            }

            static function hide_default_wc_badges_enabled() {
                return get_option('woobadges_hide_default_wc_badges', '') === '1';
            }

            function get_preset_options() {
                $options = array();
                $presets = self::get_presets();

                foreach($presets as $preset) {
                    if(empty($preset['id']) or empty($preset['name'])) { continue; }

                    $options[$preset['id']] = $preset['name'];
                }

                return $options;
            }

            function sanitize_badge_values($values) {
                $defaults = self::get_default_badge_data();
                $values = is_array($values) ? $values : array();
                $values = array_merge($defaults, $values);

                $values['position'] = in_array($values['position'], self::get_positions()) ? $values['position'] : 'none';
                $values['source'] = in_array($values['source'], array('manual', 'automatic')) ? $values['source'] : 'manual';
                $values['automaticRule'] = in_array($values['automaticRule'], self::get_automatic_rules()) ? $values['automaticRule'] : 'none';
                $values['shape'] = in_array($values['shape'], self::get_shapes()) ? $values['shape'] : 'default';
                $values['opacity'] = (string) max(0, min(1, floatval($values['opacity'])));
                $values['text'] = sanitize_text_field($values['text']);
                $values['background'] = sanitize_hex_color($values['background']) ? sanitize_hex_color($values['background']) : $defaults['background'];
                $values['color'] = sanitize_hex_color($values['color']) ? sanitize_hex_color($values['color']) : $defaults['color'];
                $font_size = sanitize_text_field($values['fontSize']);
                $values['fontSize'] = preg_match('/^(?:\d+(?:\.\d+)?|\.\d+)(?:px|em|rem|%|pt|vw|vh)$/i', $font_size) ? $font_size : $defaults['fontSize'];
                $values['zoomSingleProduct'] = $values['zoomSingleProduct'] === '' ? $defaults['zoomSingleProduct'] : (string) max(0.5, min(5, floatval($values['zoomSingleProduct'])));
                $font_weight = sanitize_text_field($values['fontWeight']);
                $values['fontWeight'] = preg_match('/^(?:normal|bold|bolder|lighter|[1-9][0-9]{0,2}|1000)$/', $font_weight) ? $font_weight : $defaults['fontWeight'];
                $values['showSingle'] = empty($values['showSingle']) ? '' : '1';

                return $values;
            }

            function resolve_badge_values($product, $values) {
                $values = $this->sanitize_badge_values($values);

                if($values['position'] == 'none') { return false; }

                if($values['source'] == 'automatic') {
                    return $this->get_automatic_badge_values($product, $values);
                }

                if(trim($values['text']) !== '') {
                    return $values;
                }

                if($values['automaticRule'] != 'none') {
                    return $this->get_automatic_badge_values($product, $values);
                }

                return false;
            }

            function get_badge_values($product) {
                if(!$product instanceof WC_Product) { return false; }

                $woobadges_values = get_post_meta($product->get_id(), 'woobadge_product', true);
                $product_badge = $this->resolve_badge_values($product, $woobadges_values);

                if($product_badge) {
                    return $product_badge;
                }

                if(is_array($woobadges_values) && !empty($woobadges_values['preset'])) {
                    $preset_badge = $this->get_preset_badge_values($product, $woobadges_values['preset']);

                    if($preset_badge) {
                        return $preset_badge;
                    }
                }

                return $this->get_automatic_preset_badge_values($product);
            }

            function get_automatic_badge_values($product, $woobadges_values) {
                $automatic_rule = isset($woobadges_values['automaticRule']) ? $woobadges_values['automaticRule'] : 'none';

                if($automatic_rule == 'none' or !$this->match_automatic_rule($product, $automatic_rule)) {
                    return false;
                }

                if(!isset($woobadges_values['text']) || trim($woobadges_values['text']) === '') {
                    $woobadges_values['text'] = $this->get_automatic_badge_text($automatic_rule, $product);
                }

                return $woobadges_values;
            }

            function match_automatic_rule($product, $automatic_rule) {
                if($automatic_rule == 'sale') {
                    return $product->is_on_sale();
                }

                if($automatic_rule == 'featured') {
                    return $product->is_featured();
                }

                if($automatic_rule == 'outofstock') {
                    return !$product->is_in_stock();
                }

                if($automatic_rule == 'new') {
                    $date_created = $product->get_date_created();
                    $newness_days = absint(apply_filters('woobadges_newness_days', 30, $product));

                    if(!$date_created or !$newness_days) {
                        return false;
                    }

                    return $date_created->getTimestamp() >= ( current_time('timestamp') - ( DAY_IN_SECONDS * $newness_days ) );
                }

                return false;
            }

            function get_automatic_badge_text($automatic_rule, $product) {
                $automatic_texts = array(
                    'sale' => __('Sale', 'badges-woo'),
                    'featured' => __('Featured', 'badges-woo'),
                    'outofstock' => __('Out of stock', 'badges-woo'),
                    'new' => __('New', 'badges-woo')
                );

                $text = isset($automatic_texts[$automatic_rule]) ? $automatic_texts[$automatic_rule] : '';

                return apply_filters('woobadges_automatic_badge_text', $text, $automatic_rule, $product);
            }

            function get_preset_badge_values($product, $preset_id) {
                foreach(self::get_presets() as $preset) {
                    if(empty($preset['id']) or $preset['id'] != $preset_id) { continue; }
                    if(isset($preset['enabled']) && empty($preset['enabled'])) { return false; }

                    return $this->resolve_badge_values($product, $preset);
                }

                return false;
            }

            function get_automatic_preset_badge_values($product) {
                foreach(self::get_presets() as $preset) {
                    if(isset($preset['enabled']) && empty($preset['enabled'])) { continue; }
                    if(empty($preset['autoApply'])) { continue; }
                    if(empty($preset['automaticRule']) or $preset['automaticRule'] == 'none') { continue; }

                    if(!$this->match_automatic_rule($product, $preset['automaticRule'])) { continue; }

                    $preset_badge = $this->resolve_badge_values($product, $preset);

                    if($preset_badge) {
                        return $preset_badge;
                    }
                }

                return false;
            }

            function get_badge_styles($woobadges_values, $include_zoom = false) {
                $styles = array();

                $styles[] = 'background-color:' . ( isset($woobadges_values['background']) ? $woobadges_values['background'] : '' );
                $styles[] = 'color:' . ( isset($woobadges_values['color']) ? $woobadges_values['color'] : '' );
                $styles[] = 'font-size:' . ( isset($woobadges_values['fontSize']) && $woobadges_values['fontSize'] != '' ? $woobadges_values['fontSize'] : '12px' );
                $styles[] = 'font-weight:' . ( isset($woobadges_values['fontWeight']) ? $woobadges_values['fontWeight'] : 'normal' );

                if(isset($woobadges_values['opacity']) && $woobadges_values['opacity'] != '') {
                    $styles[] = 'opacity:' . $woobadges_values['opacity'];
                }

                if($include_zoom) {
                    $styles[] = 'zoom:' . ( isset($woobadges_values['zoomSingleProduct']) && $woobadges_values['zoomSingleProduct'] != '' ? $woobadges_values['zoomSingleProduct'] : '1' );
                }

                return implode(';', $styles);
            }

            function get_badge_classes($woobadges_values) {
                $shape = isset($woobadges_values['shape']) ? $woobadges_values['shape'] : 'default';

                return trim($woobadges_values['position'] . ' badge badge-shape-' . $shape);
            }
    
            function get_image($image, $product, $size, $attr, $placeholder, $image2){
                if(is_admin()) { return $image;}
                if(is_cart()) { return $image; }
                if(!$product || strpos($image, 'badge-position') !== false) { return $image; }

                $woobadges_values = $this->get_badge_values($product);
    
                if(!$woobadges_values) { return $image; }

                return '<div class="badge-position" style="position:relative">
                            <div class="badge-overlay">
                                <span class="'.esc_attr($this->get_badge_classes($woobadges_values)).'" style="'.esc_attr($this->get_badge_styles($woobadges_values)).'">'.esc_html($woobadges_values['text']).'</span>
                            </div>
                        '.$image.'
                        </div>'; 
            }

            function get_loop_image($image) {
                global $product;

                if(!$product && get_the_ID()) {
                    $product = wc_get_product(get_the_ID());
                }

                if(!$product) {
                    return $image;
                }

                return $this->get_image($image, $product, '', array(), false, null);
            }

            function filter_post_thumbnail_html($html, $post_id, $post_thumbnail_id, $size, $attr) {
                if(is_admin() || is_cart() || is_product()) {
                    return $html;
                }

                if(!is_shop() && !is_product_taxonomy() && !is_product_category() && !is_product_tag()) {
                    return $html;
                }

                if(get_post_type($post_id) !== 'product') {
                    return $html;
                }

                $product = wc_get_product($post_id);

                if(!$product) {
                    return $html;
                }

                return $this->get_image($html, $product, $size, $attr, false, $html);
            }

            function maybe_hide_default_sale_flash($html, $post, $product) {
                if(!self::hide_default_wc_badges_enabled()) {
                    return $html;
                }

                if(!$product instanceof WC_Product) {
                    return $html;
                }

                $badge = $this->get_badge_values($product);

                if(!$badge || is_admin() || is_cart()) { return $html; }
                if(is_product() && $product->get_id() == get_queried_object_id() && empty($badge['showSingle'])) { return $html; }

                return '';
            }

            function show_on_single_product($image, $attchID){
                if(is_admin()) { return $image;}
                if(is_cart()) { return $image; }
                if(!is_product()) { return $image; }

                global $product;

                $woobadges_values = $this->get_badge_values($product);
                if(!$woobadges_values) { return $image; }
                
                if(empty($woobadges_values['showSingle'])) {
                    return $image;
                }

                if(!$this->featuredImage or $this->featuredImage != $product->get_id()) {
                    $this->featuredImage = $product->get_id();
                }else{
                    return $image;
                }


                return '<div class="with-badges master-badge-container">
                            <div class="badge-overlay">
                                <span class="'.esc_attr($this->get_badge_classes($woobadges_values)).'" style="'.esc_attr($this->get_badge_styles($woobadges_values, true)).'">'.esc_html($woobadges_values['text']).'</span>
                            </div>
                        </div>'.$image; 
            }

            function show_single_product_badges($classes){
                global $product;

                $woobadges_values = $this->get_badge_values($product);

                if(!$woobadges_values or empty($woobadges_values['showSingle'])) {
                    return $classes;
                }

                $classes[] = 'wrapper-with-badges';
                return $classes;
            }
    
            function metabox_product_badge() {
                add_meta_box( 'woobadgeproduct', __( 'Badge Configuration', 'badges-woo' ), array($this, 'show_config_product'), 'product', 'side' );
            }

            function register_settings_page() {
                add_submenu_page(
                    'woocommerce',
                    __('Badges Woo', 'badges-woo'),
                    __('Badges Woo', 'badges-woo'),
                    'manage_options',
                    'woobadges-settings',
                    array($this, 'render_settings_page')
                );
            }

            function register_settings() {
                register_setting('woobadges_settings_group', 'woobadges_presets', array($this, 'sanitize_presets_option'));
                register_setting('woobadges_settings_group', 'woobadges_hide_default_wc_badges');
            }

            function sanitize_presets_option($presets) {
                $sanitized = array();

                if(!is_array($presets)) {
                    return $sanitized;
                }

                $used_ids = array();

                foreach($presets as $preset) {
                    if(!is_array($preset)) { continue; }

                    $name = isset($preset['name']) ? sanitize_text_field($preset['name']) : '';
                    if($name === '') { continue; }

                    $sanitized_preset = $this->sanitize_badge_values($preset);
                    $preset_id = isset($preset['id']) ? sanitize_title($preset['id']) : '';
                    if($preset_id === '' || isset($used_ids[$preset_id])) {
                        do {
                            $preset_id = wp_generate_uuid4();
                        } while(isset($used_ids[$preset_id]));
                    }
                    $used_ids[$preset_id] = true;
                    $sanitized_preset['id'] = $preset_id;
                    $sanitized_preset['name'] = $name;
                    $sanitized_preset['autoApply'] = empty($preset['autoApply']) ? '' : '1';
                    $sanitized_preset['enabled'] = !isset($preset['enabled']) || !empty($preset['enabled']) ? '1' : '0';

                    $sanitized[] = $sanitized_preset;
                }

                return $sanitized;
            }
    
            function save_badges_product($post_id){
                if((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id) || wp_is_post_autosave($post_id)) { return; }
                if(get_post_type($post_id) !== 'product' || !current_user_can('edit_post', $post_id)) { return; }
                if(!isset($_POST['woobadges_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['woobadges_nonce'])), 'woobadges_save_product')) { return; }
                if(!isset($_POST['woobadges_position'])) { return; }

                $badgeInfo = self::get_default_badge_data();
                foreach($badgeInfo as $key => $default) {
                    $field = 'woobadges_' . $key;
                    $badgeInfo[$key] = isset($_POST[$field]) ? sanitize_text_field(wp_unslash($_POST[$field])) : $default;
                }
                $badgeInfo['showSingle'] = isset($_POST['woobadges_showSingle']) ? '1' : '';
                $sanitized_badge = $this->sanitize_badge_values($badgeInfo);
                $sanitized_badge['preset'] = isset($_POST['woobadges_preset']) ? sanitize_text_field(wp_unslash($_POST['woobadges_preset'])) : '';
                update_post_meta($post_id, 'woobadge_product', wp_slash($sanitized_badge));
            }
            function show_config_product(){
                require_once(WOOBADGES_PATH . 'product_metabox.php');
            }

            function render_settings_page() {
                require_once(WOOBADGES_PATH . 'settings-page.php');
            }
    
            function load_styles() {
                wp_enqueue_style( 'badge-styles', WOOBADGES_URL . 'styles.css', array(), WOOBADGES_VERSION );
                if(!is_product()) { return; }
                wp_enqueue_script( 'woobadges-script', plugins_url('frontend.scripts.js', __FILE__ ), array( 'jquery' ), WOOBADGES_VERSION, true );

            }
            function load_script_admin(){
                $screen = get_current_screen();
                if(!$screen || !(($screen->base === 'post' && $screen->post_type === 'product') || $screen->id === 'woocommerce_page_woobadges-settings')) { return; }

                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'woobadges-admin-script', plugins_url('scripts.js', __FILE__ ), array( 'wp-color-picker' ), WOOBADGES_VERSION, true );
                if($screen->id === 'woocommerce_page_woobadges-settings') {
                    wp_enqueue_style('badge-styles', WOOBADGES_URL . 'styles.css', array(), WOOBADGES_VERSION);
                    wp_localize_script('woobadges-admin-script', 'woobadgesAdmin', array(
                        'newPreset' => __('New preset', 'badges-woo'),
                        'disabled' => __('Disabled', 'badges-woo'),
                        'hidden' => __('Hidden: choose a position', 'badges-woo'),
                        'global' => __('Global application', 'badges-woo'),
                        'assigned' => __('Product assignment', 'badges-woo'),
                        'globalHelp' => __('Applies to all products matching: %s. Higher-priority product badges and selected presets win.', 'badges-woo'),
                        'conditionalHelp' => __('When assigned to a product, appears only if it matches: %s.', 'badges-woo'),
                        'manualHelp' => __('Appears with your custom text when selected in a product. The condition does not restrict this manual assignment.', 'badges-woo'),
                        'fallbackHelp' => __('With empty text, the condition must match and its label is used.', 'badges-woo'),
                        'disabledHelp' => __('This preset is disabled. Its design and assignments are kept; other eligible presets may apply instead.', 'badges-woo'),
                        'ruleRequired' => __('Choose a condition for automatic application.', 'badges-woo'),
                        'textRequired' => __('Enter badge text or choose a condition to use its label.', 'badges-woo'),
                        'preview' => __('Badge text', 'badges-woo'),
                        'copyName' => __('%s (copy)', 'badges-woo'),
                        'unsaved' => __('Unsaved changes', 'badges-woo'),
                        'remove' => __('Remove this preset? Products assigned to it may use a matching global preset instead. This takes effect after saving.', 'badges-woo')
                    ));
                }

            }
    
        }
        
        global $WOOBADGES;
        $WOOBADGES = new WOOBADGES();
    }   
}, 0);
