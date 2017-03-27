/* global redux_change */
(function ($) {
    "use strict";

    redux.field_objects = redux.field_objects || {};
    redux.field_objects.select_label = redux.field_objects.select_label || {};

    $(document).ready(function () {
        //menu manager functionality
        redux.field_objects.select_label.init();
    });

    redux.field_objects.select_label.init = function (selector) {

        if (!selector) {
            selector = $(document).find(".redux-group-tab:visible").find('.redux-container-select_label:visible');
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

            var default_params = {
                width: 'resolve',
                triggerChange: true,
                allowClear: true,
                placeholder: "Please select icon"
            };

            default_params = $.extend({}, {formatResult: redux.field_objects.select_text.addIcon, formatSelection: redux.field_objects.select_text.addIcon, escapeMarkup: function (m) {
                    return m;
                }}, default_params);

            $(el).find('.select-label-select').select2(default_params);

            $(el).find('.select-label-select').on('change', function (e) {
                $(this).closest('li').find('.value-store').val($(this).val());
            });
        });



    };

    redux.field_objects.select_label.addIcon = function (icon) {
        if (icon.hasOwnProperty('id')) {
            return "<span><i class='" + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.id.toUpperCase() + "</span>";
        }
    };
})(jQuery);