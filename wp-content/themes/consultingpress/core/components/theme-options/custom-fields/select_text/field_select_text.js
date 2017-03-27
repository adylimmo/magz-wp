/* global redux_change, redux */

(function ($) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.select_text = redux.field_objects.select_text || {};

    $(document).ready(
            function () {
                redux.field_objects.select_text.init();
            }
    );

    redux.field_objects.select_text.init = function (selector) {
        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-select_text:visible');
        }

        $(selector).each(function () {

            var el = $(this);
            var parent = el;

            if (!el.hasClass('redux-field-container')) {
                parent = el.parents('.redux-field-container:first');
            }
            if (parent.is(":hidden")) { // Skip hidden fields
                return;
            }
            if (parent.hasClass('redux-field-init')) {
                parent.removeClass('redux-field-init');
            } else {
                return;
            }

            var default_params;

            default_params = {
                width: 'resolve',
                triggerChange: true,
                allowClear: true,
                placeholder: "Select icon"
            };

            default_params = $.extend({}, {formatResult: redux.field_objects.select_text.addIcon, formatSelection: redux.field_objects.select_text.addIcon, escapeMarkup: function (m) {
                    return m;
                }}, default_params);

            $(el).find('.redux-select-text-remove').on('click', function () {
                redux_change($(this));
                $(this).prev('input[type="text"]').val('');
                $(this).parent().slideUp('medium', function () {
                    $(this).remove();
                });
            });

            $(el).find('.redux-select-text-add').on('click', function () {

                var number = parseInt($(this).attr('data-add_number'));
                var id = $(this).attr('data-id');
                var name = $(this).attr('data-name');

                for (var i = 0; i < number; i++) {
                    // clone the field
                    var new_input = $(el).find('#' + id + ' li:last-child').clone();

                    // clear the values
                    new_input.find('input[type="text"]').val('');
                    
                    // remove old select2 container
                    new_input.find('.select2-container').remove();
                    
                    // init new select2 field
                    new_input.find('select').css('width', 168 + "px").prop('selectedIndex', 0).select2(default_params);
                    
                    // show new field
                    new_input.find('.select2-container').css('display', 'inline-block');

                    // append to html
                    $('#' + id).append(new_input);
                    
                }
            });

            $(this).find('.select-text-select').select2(default_params);
        });


    };

    redux.field_objects.select_text.addIcon = function (icon) {
        if (icon.hasOwnProperty('id')) {
            return "<span class='elusive'><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.text + "</span>";
        }
    };
})(jQuery);