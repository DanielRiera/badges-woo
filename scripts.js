jQuery(document).ready(function($){
    function initColorPicker(context) {
        $(context).find('.colorPicker').wpColorPicker();
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

    $(document).on("click", ".woobadges-add-preset", function(e){
        var $wrapper = $("#woobadges-presets-wrapper");
        var template = $("#woobadges-preset-template").html();
        var index = $wrapper.find(".woobadges-preset-item").length;

        e.preventDefault();
        template = template.replace(/__index__/g, index);
        $wrapper.append(template);
        initColorPicker($wrapper.children().last());
        $wrapper.children().last().find("input[type='range']").each(function(){
            updateSliderValue(this);
        });
    });

    $(document).on("click", ".woobadges-remove-preset", function(e){
        e.preventDefault();
        $(this).closest(".woobadges-preset-item").remove();
    });

    $("input[type='range'][data-target]").each(function(){
        updateSliderValue(this);
    });
});
