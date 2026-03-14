<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$positions = WOOBADGES::get_positions();
$shapes = WOOBADGES::get_shapes();
$automatic_rules = WOOBADGES::get_automatic_rules();
$presets = WOOBADGES::get_presets();
$defaults = WOOBADGES::get_default_badge_data();
$preview_image = function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src('woocommerce_thumbnail') : WOOBADGES_URL . 'wordpress-org/icon-128x128.png';
$automatic_rule_labels = array(
    'none' => __('None', 'badges-woo'),
    'sale' => __('Sale', 'badges-woo'),
    'featured' => __('Featured', 'badges-woo'),
    'outofstock' => __('Out of stock', 'badges-woo'),
    'new' => __('New', 'badges-woo')
);
?>
<div class="wrap">
    <div class="woobadges-settings-hero">
        <div>
            <h4 style="font-size: 36px"><?= esc_html__('Badges Woo', 'badges-woo') ?></h4>
            <p><?= esc_html__('Create reusable preset badges and apply them automatically when products match your rules.', 'badges-woo') ?></p>
        </div>
        <a href="#" class="button button-primary button-hero woobadges-add-preset"><?= esc_html__('Add preset', 'badges-woo') ?></a>
    </div>
    <form method="post" action="options.php">
        <?php settings_fields('woobadges_settings_group'); ?>
        <div class="woobadges-general-card">
            <h4><?= esc_html__('General settings', 'badges-woo') ?></h4>
            <label class="woobadges-toggle">
                <input type="checkbox" name="woobadges_hide_default_wc_badges" value="1" <?= checked(WOOBADGES::hide_default_wc_badges_enabled(), true, false) ?>>
                <span>
                    <strong><?= esc_html__('Hide default WooCommerce badges when a custom badge exists', 'badges-woo') ?></strong>
                    <small><?= esc_html__('Removes the native WooCommerce sale badge if the product already has a badge from Badges Woo.', 'badges-woo') ?></small>
                </span>
            </label>
        </div>
        <div id="woobadges-presets-wrapper">
            <?php foreach($presets as $index => $preset) {
                $preset = array_merge($defaults, $preset);
                ?>
                <div class="woobadges-preset-item postbox">
                    <div class="woobadges-preset-head">
                        <h3><?= esc_html__('Preset', 'badges-woo') ?></h3>
                        <a href="#" class="button woobadges-remove-preset"><?= esc_html__('Remove', 'badges-woo') ?></a>
                    </div>
                    <p>
                        <label><?= esc_html__('Name', 'badges-woo') ?></label><br>
                        <input type="text" class="regular-text" name="woobadges_presets[<?= esc_attr($index) ?>][name]" value="<?= esc_attr($preset['name']) ?>">
                        <input type="hidden" name="woobadges_presets[<?= esc_attr($index) ?>][id]" value="<?= esc_attr($preset['id']) ?>">
                    </p>
                    <div class="woobadge-config">
                        <h4><?= esc_html__('Position', 'badges-woo') ?></h4>
                        <div class="row">
                            <?php foreach($positions as $position) { ?>
                                <label class="col featured-image-woobadges" style="background-image:url(<?= esc_url($preview_image) ?>)">
                                    <input type="radio" class="woobadges_radio" name="woobadges_presets[<?= esc_attr($index) ?>][position]" value="<?= esc_attr($position) ?>" <?= checked($preset['position'], $position, false) ?>>
                                    <span class="woobadges-position-preview"><span class="woobadges-position-badge woobadges-preview-<?= esc_attr($position) ?>"></span></span>
                                </label>
                            <?php } ?>
                        </div>
                    </div>
                    <p>
                        <label><?= esc_html__('Text', 'badges-woo') ?></label><br>
                        <input type="text" class="regular-text" name="woobadges_presets[<?= esc_attr($index) ?>][text]" value="<?= esc_attr($preset['text']) ?>">
                    </p>
                    <p>
                        <label><?= esc_html__('Source', 'badges-woo') ?></label><br>
                        <select name="woobadges_presets[<?= esc_attr($index) ?>][source]">
                            <option value="manual" <?= selected($preset['source'], 'manual', false) ?>><?= esc_html__('Manual', 'badges-woo') ?></option>
                            <option value="automatic" <?= selected($preset['source'], 'automatic', false) ?>><?= esc_html__('Automatic', 'badges-woo') ?></option>
                        </select>
                    </p>
                    <p>
                        <label><?= esc_html__('Shape', 'badges-woo') ?></label><br>
                        <div class="woobadges-shapes-grid">
                            <?php foreach($shapes as $shape) { ?>
                                <label class="woobadges-shape-option">
                                    <input type="radio" class="woobadges_radio" name="woobadges_presets[<?= esc_attr($index) ?>][shape]" value="<?= esc_attr($shape) ?>" <?= checked($preset['shape'], $shape, false) ?>>
                                    <div class="woobadges-shape-preview">
                                        <div class="woobadges-shape-badge woobadges-shape-<?= esc_attr($shape) ?>"></div>
                                    </div>
                                </label>
                            <?php } ?>
                        </div>
                    </p>
                    <p>
                        <label><?= esc_html__('Automatic rule', 'badges-woo') ?></label><br>
                        <select name="woobadges_presets[<?= esc_attr($index) ?>][automaticRule]">
                            <?php foreach($automatic_rules as $automatic_rule) { ?>
                                <option value="<?= esc_attr($automatic_rule) ?>" <?= selected($preset['automaticRule'], $automatic_rule, false) ?>><?= esc_html($automatic_rule_labels[$automatic_rule]) ?></option>
                            <?php } ?>
                        </select>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="woobadges_presets[<?= esc_attr($index) ?>][autoApply]" value="1" <?= checked(!empty($preset['autoApply']), true, false) ?>>
                            <?= esc_html__('Apply automatically when the rule matches', 'badges-woo') ?>
                        </label>
                    </p>
                    <p>
                        <label><?= esc_html__('Background color', 'badges-woo') ?></label><br>
                        <input type="text" class="colorPicker" name="woobadges_presets[<?= esc_attr($index) ?>][background]" value="<?= esc_attr($preset['background']) ?>" data-default-color="#333333">
                    </p>
                    <p>
                        <label><?= esc_html__('Text color', 'badges-woo') ?></label><br>
                        <input type="text" class="colorPicker" name="woobadges_presets[<?= esc_attr($index) ?>][color]" value="<?= esc_attr($preset['color']) ?>" data-default-color="#FFFFFF">
                    </p>
                    <p>
                        <label><?= esc_html__('Opacity', 'badges-woo') ?> <span class="woobadges_opacity_value"><?= esc_html($preset['opacity']) ?></span></label><br>
                        <span class="slidecontainer">
                            <input type="range" min="0" max="1" step="0.1" class="slider" data-target="woobadges_opacity_value" name="woobadges_presets[<?= esc_attr($index) ?>][opacity]" value="<?= esc_attr($preset['opacity']) ?>">
                        </span>
                    </p>
                    <p>
                        <label><?= esc_html__('Font size', 'badges-woo') ?></label><br>
                        <input type="text" class="regular-text" name="woobadges_presets[<?= esc_attr($index) ?>][fontSize]" value="<?= esc_attr($preset['fontSize']) ?>">
                    </p>
                    <p>
                        <label><?= esc_html__('Font weight', 'badges-woo') ?></label><br>
                        <input type="text" class="regular-text" name="woobadges_presets[<?= esc_attr($index) ?>][fontWeight]" value="<?= esc_attr($preset['fontWeight']) ?>">
                    </p>
                    <p>
                        <label><?= esc_html__('Single product zoom', 'badges-woo') ?> <span class="woobadges_zoom_value"><?= esc_html($preset['zoomSingleProduct']) ?></span></label><br>
                        <span class="slidecontainer">
                            <input type="range" min="0.5" max="5" step="0.1" class="slider" data-target="woobadges_zoom_value" name="woobadges_presets[<?= esc_attr($index) ?>][zoomSingleProduct]" value="<?= esc_attr($preset['zoomSingleProduct']) ?>">
                        </span>
                    </p>
                    <p>
                        <label>
                            <input type="checkbox" name="woobadges_presets[<?= esc_attr($index) ?>][showSingle]" value="1" <?= checked(!empty($preset['showSingle']), true, false) ?>>
                            <?= esc_html__('Show on single product page', 'badges-woo') ?>
                        </label>
                    </p>
                </div>
            <?php } ?>
        </div>
        <?php submit_button(); ?>
    </form>
</div>
<script type="text/html" id="woobadges-preset-template">
<div class="woobadges-preset-item postbox">
    <div class="woobadges-preset-head">
        <h2><?= esc_html__('Preset', 'badges-woo') ?></h2>
        <a href="#" class="button woobadges-remove-preset"><?= esc_html__('Remove', 'badges-woo') ?></a>
    </div>
    <p>
        <label><?= esc_html__('Name', 'badges-woo') ?></label><br>
        <input type="text" class="regular-text" name="woobadges_presets[__index__][name]" value="">
        <input type="hidden" name="woobadges_presets[__index__][id]" value="">
    </p>
    <div class="woobadge-config">
        <h4><?= esc_html__('Position', 'badges-woo') ?></h4>
        <div class="row">
            <?php foreach($positions as $position) { ?>
                <label class="col featured-image-woobadges" style="background-image:url(<?= esc_url($preview_image) ?>)">
                    <input type="radio" class="woobadges_radio" name="woobadges_presets[__index__][position]" value="<?= esc_attr($position) ?>" <?= checked($defaults['position'], $position, false) ?>>
                    <span class="woobadges-position-preview"><span class="woobadges-position-badge woobadges-preview-<?= esc_attr($position) ?>"></span></span>
                </label>
            <?php } ?>
        </div>
    </div>
    <p>
        <label><?= esc_html__('Text', 'badges-woo') ?></label><br>
        <input type="text" class="regular-text" name="woobadges_presets[__index__][text]" value="">
    </p>
    <p>
        <label><?= esc_html__('Source', 'badges-woo') ?></label><br>
        <select name="woobadges_presets[__index__][source]">
            <option value="manual"><?= esc_html__('Manual', 'badges-woo') ?></option>
            <option value="automatic"><?= esc_html__('Automatic', 'badges-woo') ?></option>
        </select>
    </p>
    <p>
        <label><?= esc_html__('Shape', 'badges-woo') ?></label><br>
        <div class="woobadges-shapes-grid">
            <?php foreach($shapes as $shape) { ?>
                <label class="woobadges-shape-option">
                    <input type="radio" class="woobadges_radio" name="woobadges_presets[__index__][shape]" value="<?= esc_attr($shape) ?>" <?= checked($defaults['shape'], $shape, false) ?>>
                    <div class="woobadges-shape-preview">
                        <div class="woobadges-shape-badge woobadges-shape-<?= esc_attr($shape) ?>"></div>
                    </div>
                </label>
            <?php } ?>
        </div>
    </p>
    <p>
        <label><?= esc_html__('Automatic rule', 'badges-woo') ?></label><br>
        <select name="woobadges_presets[__index__][automaticRule]">
            <?php foreach($automatic_rules as $automatic_rule) { ?>
                <option value="<?= esc_attr($automatic_rule) ?>" <?= selected($defaults['automaticRule'], $automatic_rule, false) ?>><?= esc_html($automatic_rule_labels[$automatic_rule]) ?></option>
            <?php } ?>
        </select>
    </p>
    <p>
        <label>
            <input type="checkbox" name="woobadges_presets[__index__][autoApply]" value="1">
            <?= esc_html__('Apply automatically when the rule matches', 'badges-woo') ?>
        </label>
    </p>
    <p>
        <label><?= esc_html__('Background color', 'badges-woo') ?></label><br>
        <input type="text" class="colorPicker" name="woobadges_presets[__index__][background]" value="<?= esc_attr($defaults['background']) ?>" data-default-color="#333333">
    </p>
    <p>
        <label><?= esc_html__('Text color', 'badges-woo') ?></label><br>
        <input type="text" class="colorPicker" name="woobadges_presets[__index__][color]" value="<?= esc_attr($defaults['color']) ?>" data-default-color="#FFFFFF">
    </p>
    <p>
        <label><?= esc_html__('Opacity', 'badges-woo') ?> <span class="woobadges_opacity_value"><?= esc_html($defaults['opacity']) ?></span></label><br>
        <span class="slidecontainer">
            <input type="range" min="0" max="1" step="0.1" class="slider" data-target="woobadges_opacity_value" name="woobadges_presets[__index__][opacity]" value="<?= esc_attr($defaults['opacity']) ?>">
        </span>
    </p>
    <p>
        <label><?= esc_html__('Font size', 'badges-woo') ?></label><br>
        <input type="text" class="regular-text" name="woobadges_presets[__index__][fontSize]" value="<?= esc_attr($defaults['fontSize']) ?>">
    </p>
    <p>
        <label><?= esc_html__('Font weight', 'badges-woo') ?></label><br>
        <input type="text" class="regular-text" name="woobadges_presets[__index__][fontWeight]" value="<?= esc_attr($defaults['fontWeight']) ?>">
    </p>
    <p>
        <label><?= esc_html__('Single product zoom', 'badges-woo') ?> <span class="woobadges_zoom_value"><?= esc_html($defaults['zoomSingleProduct']) ?></span></label><br>
        <span class="slidecontainer">
            <input type="range" min="0.5" max="5" step="0.1" class="slider" data-target="woobadges_zoom_value" name="woobadges_presets[__index__][zoomSingleProduct]" value="<?= esc_attr($defaults['zoomSingleProduct']) ?>">
        </span>
    </p>
    <p>
        <label>
            <input type="checkbox" name="woobadges_presets[__index__][showSingle]" value="1">
            <?= esc_html__('Show on single product page', 'badges-woo') ?>
        </label>
    </p>
</div>
</script>
<style>
.woobadges-settings-hero {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 24px;
  margin: 20px 0 24px;
  padding: 24px 28px;
  border-radius: 20px;
  background: linear-gradient(135deg, #49164f 0%, #933eaf 55%, #c48bd6 100%);
  color: #fff;
  box-shadow: 0 18px 40px rgba(22, 50, 79, 0.18);
}
.woobadges-settings-hero h1 {
  color: #fff;
  margin: 0 0 8px;
}
.woobadges-settings-hero p {
  margin: 0;
  font-size: 14px;
  opacity: 0.92;
}
.woobadges-settings-hero .button-hero {
  white-space: nowrap;
  background: #fff;
  color: #16324f;
  border-color: #fff;
}
.woobadges-general-card {
  margin: 0 0 24px;
  padding: 18px 20px;
  border: 1px solid #d7e3ee;
  border-radius: 18px;
  background: linear-gradient(180deg, #ffffff 0%, #f6fbff 100%);
  box-shadow: 0 10px 24px rgba(31, 111, 139, 0.08);
}
.woobadges-general-card h2 {
  margin: 0 0 12px;
  color: #16324f;
}
.woobadges-toggle {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}
.woobadges-toggle input {
  margin-top: 3px;
}
.woobadges-toggle strong,
.woobadges-toggle small {
  display: block;
}
.woobadges-toggle small {
  margin-top: 4px;
  color: #557086;
}
#woobadges-presets-wrapper {
  display: grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 20px;
  align-items: start;
}
.woobadges-preset-item {
  margin: 0;
  padding: 18px;
  border: 1px solid #d7e3ee;
  border-radius: 18px;
  background: linear-gradient(180deg, #ffffff 0%, #f6fbff 100%);
  box-shadow: 0 10px 24px rgba(31, 111, 139, 0.08);
}
.woobadges-preset-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 16px;
}
.woobadges-preset-head h2 {
  margin: 0;
  font-size: 18px;
}
.woobadges-preset-item label {
  font-weight: 600;
  color: #16324f;
}
.woobadges-preset-item p {
  margin: 0 0 14px;
}
.woobadges-preset-item input[type='text'],
.woobadges-preset-item select {
  width: 100%;
  border: 1px solid #c8d8e6;
  border-radius: 10px;
}
.woobadges-preset-item .wp-picker-container {
  display: block;
}
.woobadges-preset-item .woobadge-config {
  margin-bottom: 14px;
  padding: 14px;
  border-radius: 14px;
  background: #edf6fb;
}
.woobadges-preset-item .slidecontainer {
  display: block;
  width: 100%;
}
.woobadges-preset-item .slider {
  -webkit-appearance: none;
  appearance: none;
  width: 100%;
  height: 25px;
  border-radius: 50px;
  background: #CCC;
  outline: none;
  opacity: 0.85;
  transition: opacity .2s;
}
.woobadges-preset-item .slider:hover {
  opacity: 1;
}
.woobadges-preset-item .slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  border-radius: 100px;
  background: #16324f;
  cursor: pointer;
  border: 3px solid #fff;
  box-shadow: 0 4px 12px rgba(22, 50, 79, 0.22);
}
.woobadges-preset-item .slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  border-radius: 100px;
  background: #16324f;
  cursor: pointer;
}
.woobadges-preset-item .row {
  width: 100%;
}
.woobadges-preset-item .row .col {
  width: 50px;
  height: 50px;
  float: left;
  margin: 6px;
  background-position: center !important;
  background-size: cover !important;
  background-color: #dfeaf2;
  border-radius: 12px;
  overflow: hidden;
  position: relative;
  border: 2px solid transparent;
  transition: transform .15s ease, border-color .15s ease, box-shadow .15s ease;
}
.woobadges-preset-item .row .col .woobadges-position-preview {
  display: flex;
  position: relative;
  width: 100%;
  height: 100%;
  background: rgba(255,255,255,0.82);
}
.woobadges-preset-item .row .col .woobadges-position-badge {
  position: absolute;
  background: #16324f;
  border-radius: 4px;
}
.woobadges-shapes-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 8px;
}
.woobadges-shape-option {
  display: block;
  width: 100%;
  height: 56px;
  margin: 0;
  border: 2px solid transparent;
  border-radius: 12px;
  overflow: hidden;
  background: #eef5fb;
  cursor: pointer;
  transition: border-color .15s ease, box-shadow .15s ease, transform .15s ease;
}
.woobadges-shape-option .woobadges-shape-preview {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100%;
  background: rgba(255,255,255,0.82);
}
.woobadges-shape-option .woobadges-shape-badge {
  display: block;
  width: 22px;
  height: 16px;
  background: #16324f;
}
.woobadges-shape-option .woobadges-shape-default {
  border-radius: 3px;
}
.woobadges-shape-option .woobadges-shape-rounded {
  border-radius: 8px;
}
.woobadges-shape-option .woobadges-shape-pill {
  border-radius: 999px;
}
.woobadges-shape-option .woobadges-shape-circle {
  width: 22px;
  height: 22px;
  border-radius: 999px;
}
.woobadges-shape-option .woobadges-shape-diamond {
  transform: rotate(45deg);
}
.woobadges-shape-option .woobadges-shape-star {
  width: 24px;
  height: 24px;
  clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
}
.woobadges-shape-option .woobadges-shape-hexagon {
  clip-path: polygon(25% 0%, 75% 0%, 100% 50%, 75% 100%, 25% 100%, 0% 50%);
}
.woobadges-shape-option .woobadges-shape-ticket {
  clip-path: polygon(0 0, 100% 0, 100% 28%, 92% 36%, 100% 44%, 100% 100%, 0 100%, 0 44%, 8% 36%, 0 28%);
}
.woobadges-shape-option .woobadges-shape-bookmark {
  height: 22px;
  clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 78%, 0 100%);
}
.woobadges-shape-option .woobadges-shape-burst {
  width: 24px;
  height: 24px;
  clip-path: polygon(50% 0%, 62% 12%, 78% 4%, 82% 20%, 98% 18%, 90% 34%, 100% 50%, 90% 66%, 98% 82%, 82% 80%, 78% 96%, 62% 88%, 50% 100%, 38% 88%, 22% 96%, 18% 80%, 2% 82%, 10% 66%, 0% 50%, 10% 34%, 2% 18%, 18% 20%, 22% 4%, 38% 12%);
}
.woobadges-preset-item .row:after {
  content: '';
  clear: both;
  display: block;
}
.woobadges-preset-item .woobadges_radio[type=radio] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}
.woobadges-preset-item .woobadges_radio[type=radio] + .woobadges-position-preview {
  cursor: pointer;
}
.woobadges-preset-item .woobadges_radio[type=radio]:checked + .woobadges-position-preview {
  outline: none;
}
.woobadges-preset-item .row .col:has(.woobadges_radio[type=radio]:checked) {
  border-color: #1f6f8b;
  box-shadow: 0 0 0 3px rgba(31, 111, 139, 0.2);
  transform: translateY(-1px);
}
.woobadges-shape-option:has(.woobadges_radio[type=radio]:checked) {
  border-color: #1f6f8b;
  box-shadow: 0 0 0 3px rgba(31, 111, 139, 0.2);
  transform: translateY(-1px);
}
.woobadges-preset-item .row .col:has(.woobadges_radio[type=radio]:checked)::after {
  content: '';
  position: absolute;
  border: 3px solid rgba(126, 58, 142, 1);
  border-radius: 10px;
  pointer-events: none;
}
.woobadges-preset-item .row .col .woobadges-preview-none {
  display: none;
}
.woobadges-preset-item .row .col .woobadges-preview-top,
.woobadges-preset-item .row .col .woobadges-preview-bottom,
.woobadges-preset-item .row .col .woobadges-preview-center {
  left: 6px;
  right: 6px;
  height: 8px;
}
.woobadges-preset-item .row .col .woobadges-preview-top { top: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-center { top: 21px; }
.woobadges-preset-item .row .col .woobadges-preview-bottom { bottom: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-left,
.woobadges-preset-item .row .col .woobadges-preview-right {
  top: 6px;
  bottom: 6px;
  width: 8px;
}
.woobadges-preset-item .row .col .woobadges-preview-left { left: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-right { right: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-top-left,
.woobadges-preset-item .row .col .woobadges-preview-top-right,
.woobadges-preset-item .row .col .woobadges-preview-bottom-left,
.woobadges-preset-item .row .col .woobadges-preview-bottom-right,
.woobadges-preset-item .row .col .woobadges-preview-center-left,
.woobadges-preset-item .row .col .woobadges-preview-center-right {
  width: 16px;
  height: 16px;
}
.woobadges-preset-item .row .col .woobadges-preview-top-left,
.woobadges-preset-item .row .col .woobadges-preview-left-top { top: 6px; left: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-top-right,
.woobadges-preset-item .row .col .woobadges-preview-right-top { top: 6px; right: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-bottom-left,
.woobadges-preset-item .row .col .woobadges-preview-left-bottom { bottom: 6px; left: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-bottom-right,
.woobadges-preset-item .row .col .woobadges-preview-right-bottom { bottom: 6px; right: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-center-left { top: 17px; left: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-center-right { top: 17px; right: 6px; }
.woobadges-preset-item .row .col .woobadges-preview-left-top,
.woobadges-preset-item .row .col .woobadges-preview-right-top,
.woobadges-preset-item .row .col .woobadges-preview-left-bottom,
.woobadges-preset-item .row .col .woobadges-preview-right-bottom {
  width: 28px;
  height: 8px;
  border-radius: 2px;
}
.woobadges-preset-item .row .col .woobadges-preview-left-top {
  top: 8px;
  left: -2px;
  transform: rotate(-45deg);
  transform-origin: top left;
}
.woobadges-preset-item .row .col .woobadges-preview-right-top {
  top: 8px;
  right: -2px;
  transform: rotate(45deg);
  transform-origin: top right;
}
.woobadges-preset-item .row .col .woobadges-preview-left-bottom {
  bottom: 8px;
  left: -2px;
  transform: rotate(45deg);
  transform-origin: bottom left;
}
.woobadges-preset-item .row .col .woobadges-preview-right-bottom {
  bottom: 8px;
  right: -2px;
  transform: rotate(-45deg);
  transform-origin: bottom right;
}
@media (max-width: 1400px) {
  #woobadges-presets-wrapper {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }
}
@media (max-width: 900px) {
  .woobadges-settings-hero {
    flex-direction: column;
    align-items: flex-start;
  }
  #woobadges-presets-wrapper {
    grid-template-columns: 1fr;
  }
  .woobadges-shapes-grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
</style>
