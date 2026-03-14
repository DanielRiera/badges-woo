<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $post;
$woobadges_values = get_post_meta($post->ID, 'woobadge_product', true);
$position_checked = isset($woobadges_values['position']) ? $woobadges_values['position'] : 'none';
$source_checked = isset($woobadges_values['source']) ? $woobadges_values['source'] : 'manual';
$automatic_rule_checked = isset($woobadges_values['automaticRule']) ? $woobadges_values['automaticRule'] : 'none';
$preset_checked = isset($woobadges_values['preset']) ? $woobadges_values['preset'] : '';
$shape_checked = isset($woobadges_values['shape']) ? $woobadges_values['shape'] : 'default';

$positions = WOOBADGES::get_positions();
$shapes = WOOBADGES::get_shapes();
$automatic_rules = WOOBADGES::get_automatic_rules();
$preset_options = $this->get_preset_options();
$automatic_rule_labels = array(
    'none' => __('None', 'badges-woo'),
    'sale' => __('Sale', 'badges-woo'),
    'featured' => __('Featured', 'badges-woo'),
    'outofstock' => __('Out of stock', 'badges-woo'),
    'new' => __('New', 'badges-woo')
);
?>

<div class="woobadge-config">
    <h4><?=__('Position', 'badges-woo')?></h4>
    <div class="row">

        <?php
            
            foreach($positions as $pos) {
                $preview = '<span class="woobadges-position-preview"><span class="woobadges-position-badge woobadges-preview-'.esc_attr($pos).'"></span></span>';
                echo '<label class="col featured-image-woobadges" style="background-image:url('.get_the_post_thumbnail_url($post->ID).')">
                    <input type="radio" class="woobadges_radio" name="woobadges_position" '.checked($position_checked, $pos, false).' value="'.$pos.'">
                    '.$preview.'
                </label>';
            }
        
        ?>
        
    </div>
    <br>
    <div>
        <div class="woobadges-input">
            <h4><?=__('Opacity', 'badges-woo')?> <span class="woobadges_opacity_value"><?= isset($woobadges_values['opacity']) ? $woobadges_values['opacity'] : '1' ?></span></h4>
            <div class="slidecontainer">
                <input type="range" name="woobadges_opacity" min="0" max="1" step="0.1" value="<?= isset($woobadges_values['opacity']) ? $woobadges_values['opacity'] : '1' ?>" class="slider" data-target="woobadges_opacity_value">
                <p></p>
            </div>
        </div>

        <div class="woobadges-input">
            <h4><?=__('Text', 'badges-woo')?></h4>
            <input type="text" name="woobadges_text" placeholder="Sale Off 50%" value="<?= isset($woobadges_values['text']) ? $woobadges_values['text'] : '' ?>" />
            <p class="description"><?= __('You can insert emojis, <a target="_blank" href="https://getemoji.com/">see link</a>', 'badges-woo')?></p>
        </div>

        <div class="woobadges-input">
            <h4><?=__('Badge source', 'badges-woo')?></h4>
            <select name="woobadges_source">
                <option value="manual" <?= selected($source_checked, 'manual', false) ?>><?= __('Manual', 'badges-woo') ?></option>
                <option value="automatic" <?= selected($source_checked, 'automatic', false) ?>><?= __('Automatic', 'badges-woo') ?></option>
            </select>
        </div>

        <div class="woobadges-input">
            <h4><?=__('Shape', 'badges-woo')?></h4>
            <div class="woobadges-shapes-grid">
                <?php foreach($shapes as $shape) { ?>
                    <label class="woobadges-shape-option">
                        <input type="radio" class="woobadges_radio" name="woobadges_shape" <?= checked($shape_checked, $shape, false) ?> value="<?= esc_attr($shape) ?>">
                        <div class="woobadges-shape-preview">
                            <div class="woobadges-shape-badge woobadges-shape-<?= esc_attr($shape) ?>"></div>
                        </div>
                    </label>
                <?php } ?>
            </div>
        </div>

        <div class="woobadges-input">
            <h4><?=__('Preset', 'badges-woo')?></h4>
            <select name="woobadges_preset">
                <option value=""><?= __('None', 'badges-woo') ?></option>
                <?php foreach($preset_options as $preset_id => $preset_name) { ?>
                    <option value="<?= esc_attr($preset_id) ?>" <?= selected($preset_checked, $preset_id, false) ?>><?= esc_html($preset_name) ?></option>
                <?php } ?>
            </select>
            <p class="description"><?= __('If the product badge does not apply, the selected preset will be used.', 'badges-woo')?></p>
        </div>

        <div class="woobadges-input">
            <h4><?=__('Automatic rule', 'badges-woo')?></h4>
            <select name="woobadges_automaticRule">
                <?php foreach($automatic_rules as $automatic_rule) { ?>
                    <option value="<?= esc_attr($automatic_rule) ?>" <?= selected($automatic_rule_checked, $automatic_rule, false) ?>><?= esc_html(isset($automatic_rule_labels[$automatic_rule]) ? $automatic_rule_labels[$automatic_rule] : $automatic_rule) ?></option>
                <?php } ?>
            </select>
            <p class="description"><?= __('Manual text keeps priority unless you switch the source to automatic.', 'badges-woo')?></p>
        </div>

        <div class="woobadges-input">
            <h4><?=__('Background Color', 'badges-woo')?></h4>
            <input type="text" name="woobadges_background" class="colorPicker" data-default-color="#333" value="<?= isset($woobadges_values['background']) ? $woobadges_values['background'] : '' ?>" />
        </div>

        <div class="woobadges-input">
            <h4><?=__('Color', 'badges-woo')?></h4>
            <input type="text" name="woobadges_color" class="colorPicker" data-default-color="#FFF" value="<?= isset($woobadges_values['color']) ? $woobadges_values['color'] : '' ?>" />
        </div>

        <div class="woobadges-input">
            <h4><?=__('Font Size', 'badges-woo')?></h4>
            <input type="text" name="woobadges_fontSize" placeholder="12px" value="<?= isset($woobadges_values['fontSize']) ? $woobadges_values['fontSize'] : '12px' ?>" />
        </div>

        <div class="woobadges-input">
            <h4><?=__('Font Weight', 'badges-woo')?></h4>
            <input type="text" name="woobadges_fontWeight" placeholder="normal" value="<?= isset($woobadges_values['fontWeight']) ? $woobadges_values['fontWeight'] : 'normal' ?>" />
        </div>
        
        <div class="woobadges-input">
            <h4><?=__('Show on single product page', 'badges-woo')?></h4>
            <input type="checkbox" name="woobadges_showSingle" value="1" <?= isset($woobadges_values['showSingle']) ? 'checked="checked"' : '' ?> />
        </div>

        <div class="woobadges-input">
            <h4><?=__('Zoom for single product page', 'badges-woo')?> <span class="woobadges_zoom_value"><?= isset($woobadges_values['zoomSingleProduct']) ? $woobadges_values['zoomSingleProduct'] : '1' ?></span></h4>
            <div class="slidecontainer">
                <input type="range" name="woobadges_zoomSingleProduct" min="0.5" max="5" step="0.1" value="<?= isset($woobadges_values['zoomSingleProduct']) ? $woobadges_values['zoomSingleProduct'] : '1' ?>" class="slider" data-target="woobadges_zoom_value">
                <p></p>
            </div>
        </div>

        
    </div>
</div>
<style>
.woobadges-input .slidecontainer {
  width: 100%;
}
.woobadges-input input[type='text'] {
    width: 100%;
}
.woobadges-input select {
    width: 100%;
}

.woobadges-input .slider {
  -webkit-appearance: none;
  appearance: none;
  
  height: 25px;
  border-radius:50px;
  background: #d3d3d3;
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s; 
  transition: opacity .2s;
}

#side-sortables .woobadges-input .slider {
    width: 100%;
}

.woobadges-input .slider:hover {
  opacity: 1;
}

.woobadges-input .slider::-webkit-slider-thumb {
  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  border-radius:100px;
  background: #0073AA;
  cursor: pointer;
}

.woobadges-input .slider::-moz-range-thumb {
  width: 25px;
  height: 25px;
  background: #0073AA;
  cursor: pointer;
}
.woobadge-config .row {
    width: 100%;
}

.woobadge-config .row .col {
    width: 50px;
    height: 50px;
    float: left;
    margin: 10px;
    background-position: center !important;
    background-size: cover !important;
    background:#CCC;
}

.woobadge-config .row .col .woobadges-position-preview {
    display: flex;
    position: relative;
    width: 100%;
    height: 100%;
    background: rgba(255,255,255,0.78);
}
.woobadge-config .row .col .woobadges-position-badge {
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
    background: rgba(255,255,255,0.78);
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
.woobadge-config .row .col .woobadges-preview-none {
    display: none;
}
.woobadge-config .row .col .woobadges-preview-top,
.woobadge-config .row .col .woobadges-preview-bottom,
.woobadge-config .row .col .woobadges-preview-center {
    left: 6px;
    right: 6px;
    height: 8px;
}
.woobadge-config .row .col .woobadges-preview-top { top: 6px; }
.woobadge-config .row .col .woobadges-preview-center { top: 21px; }
.woobadge-config .row .col .woobadges-preview-bottom { bottom: 6px; }
.woobadge-config .row .col .woobadges-preview-left,
.woobadge-config .row .col .woobadges-preview-right {
    top: 6px;
    bottom: 6px;
    width: 8px;
}
.woobadge-config .row .col .woobadges-preview-left { left: 6px; }
.woobadge-config .row .col .woobadges-preview-right { right: 6px; }
.woobadge-config .row .col .woobadges-preview-top-left,
.woobadge-config .row .col .woobadges-preview-top-right,
.woobadge-config .row .col .woobadges-preview-bottom-left,
.woobadge-config .row .col .woobadges-preview-bottom-right,
.woobadge-config .row .col .woobadges-preview-center-left,
.woobadge-config .row .col .woobadges-preview-center-right {
    width: 16px;
    height: 16px;
}
.woobadge-config .row .col .woobadges-preview-top-left,
.woobadge-config .row .col .woobadges-preview-left-top { top: 6px; left: 6px; }
.woobadge-config .row .col .woobadges-preview-top-right,
.woobadge-config .row .col .woobadges-preview-right-top { top: 6px; right: 6px; }
.woobadge-config .row .col .woobadges-preview-bottom-left,
.woobadge-config .row .col .woobadges-preview-left-bottom { bottom: 6px; left: 6px; }
.woobadge-config .row .col .woobadges-preview-bottom-right,
.woobadge-config .row .col .woobadges-preview-right-bottom { bottom: 6px; right: 6px; }
.woobadge-config .row .col .woobadges-preview-center-left { top: 17px; left: 6px; }
.woobadge-config .row .col .woobadges-preview-center-right { top: 17px; right: 6px; }
.woobadge-config .row .col .woobadges-preview-left-top,
.woobadge-config .row .col .woobadges-preview-right-top,
.woobadge-config .row .col .woobadges-preview-left-bottom,
.woobadge-config .row .col .woobadges-preview-right-bottom {
    width: 28px;
    height: 8px;
    border-radius: 2px;
}
.woobadge-config .row .col .woobadges-preview-left-top {
    top: 8px;
    left: -2px;
    transform: rotate(-45deg);
    transform-origin: top left;
}
.woobadge-config .row .col .woobadges-preview-right-top {
    top: 8px;
    right: -2px;
    transform: rotate(45deg);
    transform-origin: top right;
}
.woobadge-config .row .col .woobadges-preview-left-bottom {
    bottom: 8px;
    left: -2px;
    transform: rotate(45deg);
    transform-origin: bottom left;
}
.woobadge-config .row .col .woobadges-preview-right-bottom {
    bottom: 8px;
    right: -2px;
    transform: rotate(-45deg);
    transform-origin: bottom right;
}

.woobadge-config .row:after {
    content: '';
    clear: both;
    display: block;
}
/* HIDE RADIO */
.woobadges_radio[type=radio] { 
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

/* IMAGE STYLES */
.woobadges_radio[type=radio] + .woobadges-position-preview {
    cursor: pointer;
}

/* CHECKED STYLES */
.woobadges_radio[type=radio]:checked + .woobadges-position-preview {
    outline: 2px solid #279edb;
}
.woobadges-shape-option:has(.woobadges_radio[type=radio]:checked) {
    border-color: #279edb;
    box-shadow: 0 0 0 3px rgba(39, 158, 219, 0.18);
    transform: translateY(-1px);
}

</style>
