jQuery(document).ready(function($){
    function initColorPicker(context) {
        $(context).find('.colorPicker').wpColorPicker({
            change: function(event, ui) {
                $(event.target).val(ui.color.toString()).trigger('input');
            },
            clear: function(event) {
                $(event.target).val('').trigger('input');
            }
        });
    }

    function updateSliderValue(input) {
        var $input = $(input);
        var target = $input.data('target');

        if (!target) {
            return;
        }

        $input.closest('.woobadge-config, .woobadges-preset-item').find('.' + target).html($input.val());
    }

    initColorPicker(document);

    $(document).ajaxComplete(function (event, xhr, settings) {
        var match;
        if (typeof settings.data === 'string'
        && /action=get-post-thumbnail-html/.test(settings.data)
        && xhr.responseJSON && typeof xhr.responseJSON.data === 'string') {
            match = /<img[^>]+src="([^"]+)"/.exec(xhr.responseJSON.data);
            if (match !== null) {
                $(".featured-image-woobadges").css("background-image","url("+match[1]+")");
            }
        }
    });


    $(document).on("input", "input[name='woobadges_opacity'], input[name$='[opacity]']", function(){
        updateSliderValue(this);
    });

    $(document).on("input", "input[name='woobadges_zoomSingleProduct'], input[name$='[zoomSingleProduct]']", function(){
        updateSliderValue(this);
    });

    var messages = window.woobadgesAdmin || {};
    var dirty = false;

    function markDirty() {
        dirty = true;
        $(".woobadges-save-status").text(messages.unsaved);
    }

    function nextPresetIndex() {
        var index = 0;
        $("#woobadges-presets-wrapper").find('[name^="woobadges_presets["]').each(function(){
            var match = this.name.match(/^woobadges_presets\[(\d+)\]/);
            if (match) {
                index = Math.max(index, Number(match[1]) + 1);
            }
        });
        return index;
    }

    function presetField($preset, key) {
        return $preset.find('[name$="[' + key + ']"]');
    }

    function updatePreset($preset) {
        if (!$preset.length) { return; }
        var enabled = presetField($preset, 'enabled').filter(':checkbox').prop('checked');
        var global = presetField($preset, 'autoApply').prop('checked');
        var source = presetField($preset, 'source').val();
        var $rule = presetField($preset, 'automaticRule');
        var rule = $rule.val();
        var ruleLabel = $rule.find(':selected').text();
        var $text = presetField($preset, 'text');
        var text = $.trim($text.val());
        var position = presetField($preset, 'position').filter(':checked').val() || 'none';
        var shape = presetField($preset, 'shape').filter(':checked').val() || 'default';
        var summary = '';
        var error = enabled && rule === 'none' && (global || source === 'automatic');

        $preset.find('.woobadges-preset-title').text($.trim(presetField($preset, 'name').val()) || messages.newPreset);
        $preset.find('.woobadges-preset-status').text(!enabled ? messages.disabled : position === 'none' ? messages.hidden : global ? messages.global : messages.assigned);
        if (!enabled) {
            summary = messages.disabledHelp;
        } else {
            if (global) { summary = messages.globalHelp.replace('%s', ruleLabel) + ' '; }
            summary += source === 'automatic' ? messages.conditionalHelp.replace('%s', ruleLabel) : text ? messages.manualHelp : messages.fallbackHelp;
        }
        $preset.find('.woobadges-application-summary').text(error ? messages.ruleRequired : summary).toggleClass('woobadges-warning', !!error);
        $rule[0].setCustomValidity(error ? messages.ruleRequired : '');
        $text[0].setCustomValidity(enabled && !text && rule === 'none' ? messages.textRequired : '');
        $preset.find('.woobadges-live-preview .badge').attr('class', 'badge ' + position + ' badge-shape-' + shape).text(text || (rule !== 'none' ? ruleLabel : messages.preview)).css({
            backgroundColor: presetField($preset, 'background').val(),
            color: presetField($preset, 'color').val(),
            fontSize: presetField($preset, 'fontSize').val(),
            fontWeight: presetField($preset, 'fontWeight').val(),
            opacity: presetField($preset, 'opacity').val()
        }).toggle(position !== 'none');
        $preset.toggleClass('woobadges-disabled', !enabled);
    }

    function updateList() {
        var $items = $("#woobadges-presets-wrapper > .woobadges-preset-item");
        $('.woobadges-preset-count').text($items.length);
        $(".woobadges-empty-state").prop('hidden', $items.length !== 0);
        $items.each(function(index){
            $(this).find('.woobadges-move-up').prop('disabled', index === 0);
            $(this).find('.woobadges-move-down').prop('disabled', index === $items.length - 1);
        });
    }

    function addPreset($original) {
        var template = $("#woobadges-preset-template").html().replace(/__index__/g, nextPresetIndex());
        var $preset = $(template);
        if ($original) {
            $preset.find(':input[name]').each(function(){
                var key = this.name.match(/\[([^\]]+)\]$/)[1];
                var $source = presetField($original, key);
                if (this.type === 'radio') {
                    this.checked = $source.filter(':checked').val() === this.value;
                } else if (this.type === 'checkbox') {
                    this.checked = key === 'enabled' ? false : $source.filter(':checkbox').prop('checked');
                } else if (key !== 'enabled') {
                    this.value = key === 'id' ? '' : key === 'name' ? messages.copyName.replace('%s', $source.val()) : $source.val();
                }
            });
            $preset.insertAfter($original);
        } else {
            $("#woobadges-presets-wrapper").append($preset);
        }
        initColorPicker($preset);
        $preset.find('input[type="range"]').each(function(){ updateSliderValue(this); });
        updatePreset($preset);
        updateList();
        markDirty();
        presetField($preset, 'name').trigger('focus');
    }

    $(document).on('click', '.woobadges-add-preset', function(){ addPreset(); });
    $(document).on('click', '.woobadges-duplicate-preset', function(){ addPreset($(this).closest('.woobadges-preset-item')); });
    $(document).on('click', '.woobadges-remove-preset', function(){
        if (!window.confirm(messages.remove)) { return; }
        var $preset = $(this).closest('.woobadges-preset-item');
        var $next = $preset.next().length ? $preset.next() : $preset.prev();
        $preset.remove();
        updateList();
        markDirty();
        if ($next.length) { $next.find('summary').first().trigger('focus'); }
        else { $('.woobadges-add-preset').trigger('focus'); }
    });
    $(document).on('click', '.woobadges-move-up, .woobadges-move-down', function(){
        var $preset = $(this).closest('.woobadges-preset-item');
        if ($(this).hasClass('woobadges-move-up')) { $preset.insertBefore($preset.prev()); }
        else { $preset.insertAfter($preset.next()); }
        updateList();
        markDirty();
    });
    $(document).on('input change', '#woobadges-settings-form :input', function(){
        updatePreset($(this).closest('.woobadges-preset-item'));
        markDirty();
    });
    document.addEventListener('invalid', function(event){
        if ($(event.target).closest('#woobadges-settings-form').length) {
            $(event.target).parents('details').prop('open', true);
        }
    }, true);
    $(window).on('beforeunload', function(event){
        if (dirty) { event.preventDefault(); event.originalEvent.returnValue = ''; }
    });
    $('#woobadges-settings-form').on('submit', function(){ dirty = false; });
    $('#woobadges-presets-wrapper > .woobadges-preset-item').each(function(){ updatePreset($(this)); });
    updateList();

    $("input[type='range'][data-target]").each(function(){
        updateSliderValue(this);
    });
});
