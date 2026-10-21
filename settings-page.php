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
$position_labels = array(
    'none' => __('None', 'badges-woo'),
    'center' => __('Center', 'badges-woo'),
    'top' => __('Top', 'badges-woo'),
    'left' => __('Left', 'badges-woo'),
    'bottom' => __('Bottom', 'badges-woo'),
    'right' => __('Right', 'badges-woo'),
    'left-top' => __('Top left ribbon', 'badges-woo'),
    'right-top' => __('Top right ribbon', 'badges-woo'),
    'left-bottom' => __('Bottom left ribbon', 'badges-woo'),
    'right-bottom' => __('Bottom right ribbon', 'badges-woo'),
    'top-left' => __('Top left', 'badges-woo'),
    'top-right' => __('Top right', 'badges-woo'),
    'bottom-left' => __('Bottom left', 'badges-woo'),
    'bottom-right' => __('Bottom right', 'badges-woo'),
    'center-left' => __('Center left', 'badges-woo'),
    'center-right' => __('Center right', 'badges-woo')
);
$shape_labels = array(
    'default' => __('Rectangle', 'badges-woo'),
    'rounded' => __('Rounded', 'badges-woo'),
    'pill' => __('Pill', 'badges-woo'),
    'circle' => __('Circle', 'badges-woo'),
    'diamond' => __('Diamond', 'badges-woo'),
    'star' => __('Star', 'badges-woo'),
    'hexagon' => __('Hexagon', 'badges-woo'),
    'ticket' => __('Ticket', 'badges-woo'),
    'bookmark' => __('Bookmark', 'badges-woo'),
    'burst' => __('Burst', 'badges-woo')
);
$render_preset = function($index, $preset, $open = false) use ($defaults, $positions, $shapes, $automatic_rules, $automatic_rule_labels, $preview_image, $position_labels, $shape_labels) {
    $preset = array_merge($defaults, array('id' => '', 'name' => '', 'enabled' => '1', 'autoApply' => '', 'showSingle' => ''), $preset);
    $field = 'woobadges_presets[' . $index . ']';
?>
<details class="woobadges-preset-item postbox" <?= $open ? 'open' : '' ?>>
    <summary class="woobadges-preset-head">
        <span class="woobadges-card-icon dashicons dashicons-tag" aria-hidden="true"></span>
        <strong class="woobadges-preset-title"><?= esc_html($preset['name'] ?: __('New preset', 'badges-woo')) ?></strong>
        <span class="woobadges-preset-status"></span>
    </summary>
    <div class="woobadges-preset-actions">
        <button type="button" class="button woobadges-move-up"><span class="dashicons dashicons-arrow-up-alt2" aria-hidden="true"></span> <?= esc_html__('Move up', 'badges-woo') ?></button>
        <button type="button" class="button woobadges-move-down"><span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span> <?= esc_html__('Move down', 'badges-woo') ?></button>
        <button type="button" class="button woobadges-duplicate-preset"><span class="dashicons dashicons-admin-page" aria-hidden="true"></span> <?= esc_html__('Duplicate', 'badges-woo') ?></button>
        <button type="button" class="button woobadges-remove-preset"><span class="dashicons dashicons-trash" aria-hidden="true"></span> <?= esc_html__('Remove', 'badges-woo') ?></button>
    </div>
    <div class="woobadges-metadata">
    <p><label><?= esc_html__('Name', 'badges-woo') ?><br>
        <input type="text" name="<?= esc_attr($field) ?>[name]" value="<?= esc_attr($preset['name']) ?>" required>
    </label><input type="hidden" name="<?= esc_attr($field) ?>[id]" value="<?= esc_attr($preset['id']) ?>"></p>
    <p><input type="hidden" name="<?= esc_attr($field) ?>[enabled]" value="0">
        <label><input type="checkbox" role="switch" name="<?= esc_attr($field) ?>[enabled]" value="1" <?= checked(!empty($preset['enabled']), true, false) ?>> <?= esc_html__('Enable preset', 'badges-woo') ?></label>
        <span class="description"><?= esc_html__('Disable to stop using this preset everywhere without deleting its design or product assignments.', 'badges-woo') ?></span>
    </p>
    </div>
    <fieldset class="woobadges-application">
        <legend><?= esc_html__('When does it apply?', 'badges-woo') ?></legend>
        <p><label><?= esc_html__('When selected in a product', 'badges-woo') ?><br>
            <select name="<?= esc_attr($field) ?>[source]">
                <option value="manual" <?= selected($preset['source'], 'manual', false) ?>><?= esc_html__('Use custom text', 'badges-woo') ?></option>
                <option value="automatic" <?= selected($preset['source'], 'automatic', false) ?>><?= esc_html__('Only when the rule matches', 'badges-woo') ?></option>
            </select>
        </label></p>
        <p><label><?= esc_html__('Text', 'badges-woo') ?><br>
            <input type="text" name="<?= esc_attr($field) ?>[text]" value="<?= esc_attr($preset['text']) ?>">
        </label><span class="description"><?= esc_html__('Custom text is used as written. Leave it empty to use the rule label, such as Sale or New, when the rule matches.', 'badges-woo') ?></span></p>
        <p><label><?= esc_html__('Product condition', 'badges-woo') ?><br>
            <select name="<?= esc_attr($field) ?>[automaticRule]">
                <?php foreach($automatic_rules as $rule) { ?>
                    <option value="<?= esc_attr($rule) ?>" <?= selected($preset['automaticRule'], $rule, false) ?>><?= esc_html($automatic_rule_labels[$rule] ?? $rule) ?></option>
                <?php } ?>
            </select>
        </label></p>
        <p><label><input type="checkbox" role="switch" name="<?= esc_attr($field) ?>[autoApply]" value="1" <?= checked(!empty($preset['autoApply']), true, false) ?>> <?= esc_html__('Apply to all products matching this condition', 'badges-woo') ?></label>
            <span class="description"><?= esc_html__('No product assignment is needed. A product badge or a selected preset takes priority. The first matching global preset in this list wins.', 'badges-woo') ?></span>
        </p>
        <p class="woobadges-application-summary" aria-live="polite"></p>
    </fieldset>
    <details class="woobadges-design" open>
        <summary><?= esc_html__('Appearance and visibility', 'badges-woo') ?></summary>
        <div class="woobadges-design-grid"><div class="woobadges-preview-panel">
        <div class="woobadges-live-preview badge-position" style="background-image:url('<?= esc_url($preview_image) ?>')">
            <div class="badge-overlay"><span class="badge"></span></div>
        </div>
        <p class="description"><?= esc_html__('Design preview. Rules are evaluated for each product on the storefront.', 'badges-woo') ?></p>
        </div><div class="woobadges-design-controls">
        <div class="woobadge-config">
            <h4><?= esc_html__('Position', 'badges-woo') ?></h4>
            <p class="description"><?= esc_html__('Choose a visible position. None hides this preset in both product lists and product pages.', 'badges-woo') ?></p>
            <div class="row">
                <?php foreach($positions as $position) { ?>
                    <label class="col featured-image-woobadges" title="<?= esc_attr($position_labels[$position] ?? $position) ?>" style="background-image:url('<?= esc_url($preview_image) ?>')">
                        <input type="radio" class="woobadges_radio" name="<?= esc_attr($field) ?>[position]" value="<?= esc_attr($position) ?>" <?= checked($preset['position'], $position, false) ?> aria-label="<?= esc_attr($position_labels[$position] ?? $position) ?>">
                        <span class="woobadges-position-preview"><span class="woobadges-position-badge woobadges-preview-<?= esc_attr($position) ?>"></span></span>
                    </label>
                <?php } ?>
            </div>
        </div>
        <fieldset class="woobadges-shape-field">
            <legend><?= esc_html__('Shape', 'badges-woo') ?></legend>
            <div class="woobadges-shapes-grid">
                <?php foreach($shapes as $shape) { ?>
                    <label class="woobadges-shape-option" title="<?= esc_attr($shape_labels[$shape] ?? $shape) ?>">
                        <input type="radio" class="woobadges_radio" name="<?= esc_attr($field) ?>[shape]" value="<?= esc_attr($shape) ?>" <?= checked($preset['shape'], $shape, false) ?> aria-label="<?= esc_attr($shape_labels[$shape] ?? $shape) ?>">
                        <span class="woobadges-shape-preview"><span class="woobadges-shape-badge woobadges-shape-<?= esc_attr($shape) ?>"></span></span>
                    </label>
                <?php } ?>
            </div>
        </fieldset>
        <?php foreach(array('background' => __('Background color', 'badges-woo'), 'color' => __('Text color', 'badges-woo')) as $key => $label) { ?>
            <p><label><?= esc_html($label) ?><br><input type="text" class="colorPicker" name="<?= esc_attr($field) ?>[<?= esc_attr($key) ?>]" value="<?= esc_attr($preset[$key]) ?>" data-default-color="<?= esc_attr($defaults[$key]) ?>"></label></p>
        <?php } ?>
        <p><label><?= esc_html__('Opacity', 'badges-woo') ?> <span class="woobadges_opacity_value"><?= esc_html($preset['opacity']) ?></span><br>
            <input type="range" min="0" max="1" step="0.1" data-target="woobadges_opacity_value" name="<?= esc_attr($field) ?>[opacity]" value="<?= esc_attr($preset['opacity']) ?>">
        </label></p>
        <p><label><?= esc_html__('Font size', 'badges-woo') ?><br><input type="text" name="<?= esc_attr($field) ?>[fontSize]" value="<?= esc_attr($preset['fontSize']) ?>" placeholder="12px" pattern="(?:[0-9]+(?:\.[0-9]+)?|\.[0-9]+)(?:px|em|rem|%|pt|vw|vh)"></label></p>
        <p><label><?= esc_html__('Font weight', 'badges-woo') ?><br><input type="text" name="<?= esc_attr($field) ?>[fontWeight]" value="<?= esc_attr($preset['fontWeight']) ?>" placeholder="normal" pattern="normal|bold|bolder|lighter|[1-9][0-9]{0,2}|1000"></label></p>
        <p><label><input type="checkbox" role="switch" name="<?= esc_attr($field) ?>[showSingle]" value="1" <?= checked(!empty($preset['showSingle']), true, false) ?>> <?= esc_html__('Also show on the product page', 'badges-woo') ?></label>
            <span class="description"><?= esc_html__('Product lists show the badge when it applies. Enable this option to also show it over the main product gallery.', 'badges-woo') ?></span>
        </p>
        <p><label><?= esc_html__('Single product zoom', 'badges-woo') ?> <span class="woobadges_zoom_value"><?= esc_html($preset['zoomSingleProduct']) ?></span><br>
            <input type="range" min="0.5" max="5" step="0.1" data-target="woobadges_zoom_value" name="<?= esc_attr($field) ?>[zoomSingleProduct]" value="<?= esc_attr($preset['zoomSingleProduct']) ?>">
        </label></p>
        </div></div>
    </details>
</details>
<?php };
?>
<div class="wrap woobadges-settings">
    <div class="woobadges-settings-hero">
        <div class="woobadges-hero-copy"><span class="woobadges-hero-icon dashicons dashicons-tag" aria-hidden="true"></span><h1><?= esc_html__('Badges Woo', 'badges-woo') ?></h1><p><?= esc_html__('Create a design once, assign it to products or apply it automatically with a condition.', 'badges-woo') ?></p></div>
        <button type="button" class="button button-primary button-hero woobadges-add-preset"><span class="dashicons dashicons-plus-alt2" aria-hidden="true"></span> <?= esc_html__('Add preset', 'badges-woo') ?></button>
    </div>
    <div class="woobadges-general-card">
        <h2><?= esc_html__('Which badge is shown?', 'badges-woo') ?></h2>
        <ol>
            <li><?= esc_html__('Product badge: the badge configured directly in the product is checked first.', 'badges-woo') ?></li>
            <li><?= esc_html__('Selected preset: used when the product badge does not apply. Select it in the product badge settings.', 'badges-woo') ?></li>
            <li><?= esc_html__('Global preset: if neither applies, the first enabled preset matching its global condition is used, from top to bottom.', 'badges-woo') ?></li>
        </ol>
        <p><?= esc_html__('Only one badge is chosen. Its own visibility setting determines whether it also appears on the product page; a hidden badge does not trigger another preset.', 'badges-woo') ?></p>
        <details><summary><?= esc_html__('Examples: manual assignment and automatic application', 'badges-woo') ?></summary>
            <p><?= esc_html__('Manual: enter Limited edition, choose a position, leave the condition as None and global application off. Save, then select this preset in the products you want.', 'badges-woo') ?></p>
            <p><?= esc_html__('Automatic: choose Sale and enable global application. All sale products without a higher-priority badge get this preset. Custom text is optional.', 'badges-woo') ?></p>
            <p><?= esc_html__('Conditional assignment: select Only when the rule matches, choose Out of stock and leave global application off. Assign it to specific products; it appears only while they are out of stock.', 'badges-woo') ?></p>
            <p><?= esc_html__('New uses products created within the last 30 days by default. Sale follows WooCommerce sale status, Featured follows the featured flag, and Out of stock follows stock availability.', 'badges-woo') ?></p>
        </details>
    </div>
    <form method="post" action="options.php" id="woobadges-settings-form">
        <?php settings_fields('woobadges_settings_group'); ?>
        <div class="woobadges-general-card">
            <h2><?= esc_html__('General settings', 'badges-woo') ?></h2>
            <label class="woobadges-toggle">
                <input type="checkbox" role="switch" name="woobadges_hide_default_wc_badges" value="1" <?= checked(WOOBADGES::hide_default_wc_badges_enabled(), true, false) ?>>
                <span><strong><?= esc_html__('Hide default WooCommerce badges when a custom badge exists', 'badges-woo') ?></strong>
                    <small><?= esc_html__('Hides the native sale badge only when a custom badge is visible in that context. If the custom badge is disabled on the product page, the native sale badge is kept there.', 'badges-woo') ?></small>
                </span>
            </label>
        </div>
        <h2 class="woobadges-list-title"><?= esc_html__('Your presets', 'badges-woo') ?> <span class="woobadges-preset-count"><?= count($presets) ?></span></h2>
        <p><?= esc_html__('Open a preset to edit it. Move presets up or down to set global priority. Duplicates are created disabled so they do not affect the store until you enable them.', 'badges-woo') ?></p>
        <p class="woobadges-empty-state" <?= $presets ? 'hidden' : '' ?>><?= esc_html__('No presets yet. Add your first preset, name it and choose how it applies.', 'badges-woo') ?></p>
        <div id="woobadges-presets-wrapper">
            <?php foreach($presets as $index => $preset) { $render_preset($index, $preset); } ?>
        </div>
        <div class="woobadges-save-bar">
            <?php submit_button(__('Save changes', 'badges-woo'), 'primary', 'submit', false); ?>
            <span class="description"><?= esc_html__('Creation, edits, order, activation and removal take effect only after saving changes.', 'badges-woo') ?></span>
            <span class="woobadges-save-status" aria-live="polite"></span>
        </div>
    </form>
    <script type="text/html" id="woobadges-preset-template"><?php $render_preset('__index__', array('position' => 'top-right', 'enabled' => '1'), true); ?></script>
</div>
<style>
.woobadges-settings {
  --wb-primary: #6750a4;
  --wb-on-primary: #fff;
  --wb-primary-container: #eaddff;
  --wb-on-container: #21005d;
  --wb-surface: #fffbfe;
  --wb-surface-container: #f3edf7;
  --wb-text: #1d1b20;
  --wb-muted: #49454f;
  --wb-outline: #79747e;
  --wb-outline-soft: #e7e0ec;
  max-width: 1180px;
  color: var(--wb-text);
  padding-bottom: 20px;
  font-size: 14px;
  line-height: 1.6;
}
.woobadges-settings *, .woobadges-settings *::before, .woobadges-settings *::after { box-sizing: border-box; }
.woobadges-settings h1, .woobadges-settings h2, .woobadges-settings h4 { color: var(--wb-text); }
.woobadges-settings p { font-size: 14px; line-height: 1.6; }
.woobadges-settings-hero {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 28px;
  padding: 32px;
  margin: 24px 0;
  background: var(--wb-primary-container);
  border-radius: 28px;
  color: var(--wb-on-container);
}
.woobadges-hero-copy { position: relative; padding-left: 76px; }
.woobadges-hero-icon { position: absolute; left: 0; top: 4px; width: 56px; height: 56px; padding: 14px; font-size: 28px; background: var(--wb-primary); color: #fff; border-radius: 18px; }
.woobadges-settings .woobadges-settings-hero h1 { margin: 0 0 6px; padding: 0; font-size: 32px; line-height: 1.3; font-weight: 500; letter-spacing: -0.6px; color: var(--wb-on-container); }
.woobadges-settings-hero p { margin: 0; max-width: 620px; }
.woobadges-settings .button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 40px;
  padding: 8px 20px;
  border: 1px solid var(--wb-outline);
  border-radius: 24px;
  color: var(--wb-primary);
  background: transparent;
  font-size: 13px;
  font-weight: 600;
  line-height: 22px;
  box-shadow: none;
  cursor: pointer;
  transition: background-color 150ms ease, box-shadow 150ms ease;
}
.woobadges-settings .button:hover { background: #6750a410; color: var(--wb-primary); border-color: var(--wb-primary); }
.woobadges-settings .button-primary { background: var(--wb-primary); color: var(--wb-on-primary); border-color: var(--wb-primary); }
.woobadges-settings .button-primary:hover { background: #59428f; color: #fff; box-shadow: 0 2px 5px #1d1b2026; }
.woobadges-settings .button.button-hero { min-height: 48px; padding: 12px 24px; font-size: 14px; white-space: nowrap; }
.woobadges-settings .button .dashicons { font-size: 18px; width: 18px; height: 18px; }
.woobadges-settings .button:disabled { opacity: 0.45; color: var(--wb-muted) !important; border-color: var(--wb-outline-soft) !important; background: transparent !important; }
.woobadges-settings .button:focus-visible, .woobadges-settings summary:focus-visible, .woobadges-settings label:has(input[type='radio']:focus-visible) { outline: 3px solid var(--wb-primary); outline-offset: 3px; box-shadow: none; }
.woobadges-general-card { margin-bottom: 24px; padding: 24px 28px; background: var(--wb-surface); border: 1px solid var(--wb-outline-soft); border-radius: 20px; }
.woobadges-general-card h2 { margin: 0 0 16px; font-size: 20px; font-weight: 500; }
.woobadges-general-card ol { counter-reset: priority; list-style: none; margin: 0; display: grid; gap: 12px; }
.woobadges-general-card li { counter-increment: priority; position: relative; padding-left: 42px; min-height: 30px; display: flex; align-items: center; }
.woobadges-general-card li::before { content: counter(priority); position: absolute; left: 0; top: 0; width: 28px; height: 28px; display: grid; place-items: center; background: var(--wb-primary-container); color: var(--wb-on-container); border-radius: 50%; font-weight: 600; }
.woobadges-general-card details { padding: 14px 16px; background: var(--wb-surface-container); border-radius: 12px; margin-top: 16px; }
.woobadges-general-card summary { color: var(--wb-primary); font-weight: 600; cursor: pointer; }
.woobadges-general-card > p { color: var(--wb-muted); }
.woobadges-toggle { display: flex; align-items: flex-start; gap: 16px; cursor: pointer; }
.woobadges-toggle strong, .woobadges-toggle small { display: block; }
.woobadges-toggle strong { font-weight: 500; font-size: 15px; }
.woobadges-toggle small { margin-top: 6px; color: var(--wb-muted); font-size: 13px; }
.woobadges-settings .woobadges-list-title { display: flex; align-items: center; gap: 12px; font-size: 24px; font-weight: 500; margin: 32px 0 8px; }
.woobadges-preset-count { display: inline-grid; place-items: center; min-width: 30px; height: 30px; padding: 0 8px; border-radius: 10px; background: var(--wb-primary-container); color: var(--wb-on-container); font-size: 14px; }
.woobadges-settings #woobadges-presets-wrapper { display: flex; flex-direction: column; gap: 16px; margin-top: 20px; }
.woobadges-settings .woobadges-preset-item { margin: 0; padding: 0 24px 24px; width: 100%; border: 1px solid var(--wb-outline-soft); border-radius: 20px; background: var(--wb-surface); box-shadow: 0 1px 3px #1d1b2010; overflow: visible; }
.woobadges-settings .woobadges-preset-item:not([open]) { padding-bottom: 0; }
.woobadges-preset-head { display: flex; align-items: center; gap: 14px; padding: 20px 0; cursor: pointer; list-style: none; }
.woobadges-preset-head::-webkit-details-marker { display: none; }
.woobadges-preset-head::after { content: ''; width: 9px; height: 9px; border-right: 2px solid var(--wb-muted); border-bottom: 2px solid var(--wb-muted); transform: rotate(45deg); margin: 0 4px 5px 8px; flex-shrink: 0; }
.woobadges-preset-item[open] > summary::after { transform: rotate(225deg); margin-bottom: -5px; }
.woobadges-card-icon { display: grid; place-items: center; width: 44px; height: 44px; font-size: 23px; background: var(--wb-surface-container); color: var(--wb-primary); border-radius: 14px; flex-shrink: 0; }
.woobadges-preset-title { flex: 1; font-size: 17px; font-weight: 500; overflow-wrap: anywhere; min-width: 0; }
.woobadges-preset-status { padding: 4px 12px; border-radius: 8px; background: var(--wb-primary-container); color: var(--wb-on-container); font-size: 12px; font-weight: 500; }
.woobadges-disabled .woobadges-preset-status { background: var(--wb-surface-container); color: var(--wb-muted); }
.woobadges-preset-actions { display: flex; flex-wrap: wrap; gap: 8px; padding: 4px 0 20px; border-bottom: 1px solid var(--wb-outline-soft); margin-bottom: 24px; }
.woobadges-settings .woobadges-remove-preset { margin-left: auto; color: #b3261e; border-color: transparent; }
.woobadges-settings .woobadges-remove-preset:hover { background: #f9dedc; color: #b3261e; border-color: transparent; }
.woobadges-metadata { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; }
.woobadges-settings label { color: var(--wb-text); font-weight: 500; font-size: 14px; }
.woobadges-preset-item p { margin: 0 0 20px; }
.woobadges-settings input[type='text'], .woobadges-settings select { display: block; width: 100%; max-width: none; min-height: 48px; margin: 8px 0 0; padding: 10px 14px; border: 1px solid var(--wb-outline); border-radius: 6px; background-color: transparent; color: var(--wb-text); font-size: 15px; line-height: 26px; box-shadow: none; }
.woobadges-settings select { padding-right: 36px; }
.woobadges-settings input[type='text']:focus, .woobadges-settings select:focus { outline: 2px solid var(--wb-primary); outline-offset: -2px; border-color: var(--wb-primary); box-shadow: none; }
.woobadges-settings .description { display: block; margin-top: 8px; font-size: 12px; font-weight: 400; line-height: 1.6; color: var(--wb-muted); }
.woobadges-settings input[role='switch'] { appearance: none; -webkit-appearance: none; width: 44px; min-width: 44px; height: 26px; border: 2px solid var(--wb-outline); background: var(--wb-outline-soft); border-radius: 20px; padding: 0; margin: 0 10px 0 0; vertical-align: middle; box-shadow: none; position: relative; cursor: pointer; transition: background 150ms ease; }
.woobadges-settings input[role='switch']::before { content: ''; display: block; width: 16px; height: 16px; position: absolute; left: 3px; top: 3px; margin: 0; border-radius: 50%; background: var(--wb-outline); transition: transform 150ms ease; }
.woobadges-settings input[role='switch']:checked { background: var(--wb-primary); border-color: var(--wb-primary); }
.woobadges-settings input[role='switch']:checked::before { background: #fff; transform: translateX(17px); }
.woobadges-settings input[role='switch']:focus-visible { outline: 3px solid var(--wb-primary); outline-offset: 3px; }
.woobadges-settings fieldset { border: 0; margin: 8px 0 24px; padding: 24px; background: var(--wb-surface-container); border-radius: 16px; }
.woobadges-settings legend { float: left; width: 100%; padding: 0; margin-bottom: 16px; font-size: 16px; font-weight: 500; }
.woobadges-settings legend + * { clear: both; }
.woobadges-application { display: flow-root; }
.woobadges-settings .woobadges-application-summary { margin: 0; padding: 16px; border: 1px solid #d0bcff; border-radius: 12px; background: #eaddff80; color: var(--wb-on-container); font-size: 13px; }
.woobadges-settings .woobadges-warning { background: #f9dedc; color: #410e0b; border-color: #f2b8b5; }
.woobadges-design > summary { padding: 8px 0 20px; font-size: 18px; font-weight: 500; cursor: pointer; }
.woobadges-design-grid { display: grid; grid-template-columns: minmax(220px, 300px) minmax(0, 1fr); gap: 28px; align-items: start; }
.woobadges-preview-panel { position: sticky; top: 50px; padding: 16px; background: var(--wb-surface-container); border-radius: 16px; }
.woobadges-live-preview { position: relative; width: 100%; aspect-ratio: 1; background-color: #e7e0ec; background-size: cover; background-position: center; border-radius: 12px; overflow: hidden; }
.woobadges-settings .woobadge-config { padding: 16px; margin-bottom: 24px; border-radius: 16px; background: var(--wb-surface-container); }
.woobadges-settings .woobadge-config h4 { margin: 0; font-size: 16px; font-weight: 500; }
.woobadges-settings .woobadge-config p { margin-bottom: 12px; }
.woobadges-settings .wp-picker-container { display: block; margin-top: 8px; }
.woobadges-settings .wp-picker-container .button { min-height: 32px; padding: 0 8px; border-radius: 6px; }
.woobadges-settings .wp-picker-container input[type='text'] { min-height: 32px; margin: 0; padding: 0 6px; font-size: 13px; }
.woobadges-settings input[type='range'] { display: block; width: 100%; max-width: 400px; height: 28px; margin-top: 8px; accent-color: var(--wb-primary); cursor: pointer; }
.woobadges-settings .woobadges-save-bar { position: sticky; bottom: 16px; z-index: 20; display: flex; align-items: center; flex-wrap: wrap; gap: 12px 20px; padding: 16px 20px; margin-top: 24px; border-radius: 20px; background: #f3edf7f5; border: 1px solid var(--wb-outline-soft); box-shadow: 0 4px 16px #1d1b2020; }
.woobadges-save-bar .description { flex: 1; margin: 0; min-width: 180px; }
.woobadges-save-status { color: #b3261e; font-size: 12px; font-weight: 600; }
.woobadges-empty-state { padding: 32px; text-align: center; border: 1px dashed var(--wb-outline); border-radius: 20px; color: var(--wb-muted); }
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
  background: var(--wb-primary);
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
  background: var(--wb-primary);
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
  border-color: var(--wb-primary);
  box-shadow: 0 0 0 3px #d0bcff;
  transform: translateY(-1px);
}
.woobadges-shape-option:has(.woobadges_radio[type=radio]:checked) {
  border-color: var(--wb-primary);
  box-shadow: 0 0 0 3px #d0bcff;
  transform: translateY(-1px);
}
.woobadges-preset-item .row .col:has(.woobadges_radio[type=radio]:checked)::after {
  content: '';
  position: absolute;
  border: 3px solid var(--wb-primary);
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

.woobadges-settings .woobadges-shape-badge { display: block; background: var(--wb-primary); }
.woobadges-settings .row .col { background-color: #e7e0ec; border-radius: 10px; }
.woobadges-settings .woobadges-position-badge { background: var(--wb-primary); }
.woobadges-settings .row .col:has(input:checked), .woobadges-settings .woobadges-shape-option:has(input:checked) { border-color: var(--wb-primary); box-shadow: 0 0 0 2px #d0bcff; transform: none; }
.woobadges-settings .row .col:has(input:checked)::after { display: none; }
.woobadges-settings .woobadges-shapes-grid { grid-template-columns: repeat(5, minmax(0, 1fr)); }
.woobadges-settings .woobadges-shape-option { background: var(--wb-surface); border-radius: 10px; }
@media (max-width: 1100px) {
  .woobadges-settings-hero { align-items: flex-start; flex-direction: column; }
}
@media (max-width: 782px) {
  .woobadges-settings-hero { padding: 24px; border-radius: 20px; }
  .woobadges-hero-copy { padding-left: 0; }
  .woobadges-hero-icon { display: none; }
  .woobadges-general-card, .woobadges-settings .woobadges-preset-item { padding-left: 16px; padding-right: 16px; }
  .woobadges-metadata, .woobadges-design-grid { grid-template-columns: minmax(0, 1fr); gap: 16px; }
  .woobadges-preview-panel { position: static; max-width: 340px; }
  .woobadges-settings fieldset { padding: 16px; }
  .woobadges-preset-head { flex-wrap: wrap; gap: 10px; }
  .woobadges-preset-title { flex-basis: calc(100% - 60px); }
  .woobadges-preset-status { margin-left: 54px; }
  .woobadges-preset-head::after { margin-left: auto; }
  .woobadges-settings .woobadges-save-bar { bottom: 8px; padding: 12px 16px; gap: 8px; }
  .woobadges-save-bar .description { flex-basis: 100%; font-size: 11px; }
  .woobadges-settings .woobadges-remove-preset { margin-left: 0; }
}
@media (prefers-reduced-motion: reduce) {
  .woobadges-settings *, .woobadges-settings *::before { transition: none !important; }
}
</style>
