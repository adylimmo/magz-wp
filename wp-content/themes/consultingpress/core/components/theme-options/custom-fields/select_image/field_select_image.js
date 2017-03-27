(function($) {

    $('.redux-select-image-item').on('change', function(event) {
        var preview = $(this).parents('.redux-field:first').find('.redux-preview-image');

        var selected = $(this).val();
        if ($(this).val() === "") {
            preview.fadeOut('medium', function() {
                preview.attr('src', '');
            });
        } else {
            preview.hide().filter(function() {
                return $(this).data('id') == selected;
            }).fadeIn().css('visibility', 'visible');
        }
    });

})(jQuery);


