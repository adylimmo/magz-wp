jQuery(function ($) {
    $('.table-fields .import-button').on('click', function () {

        var id = $(this).attr('id');
        var layout = $("#layout").length > 0 ? $("#layout").val() : false;
        var file_number = 1;
        var total_files = 1;
        
        // add message to results field
        $('.results-field .holder').html('');

        // add class animate that will start loading animation
        $(this).closest('td').addClass('loading').append($('#circularG'));

        // Reset progress bar
        $('#voci-progress-bar').removeAttr('style');
        $('#voci-progress-bar-text').text('Please wait while uploading...');

        // disable all buttons
        $('.table-fields .import-button').prop('disabled', true);

        $.get(ajaxurl, {action: 'vlc_count_files', content_id: id, layout: layout, security: $('.fields-container #demo-nonce').val()}, function (data, status) {
            total_files = data;
            if ( total_files == 0 ) {
                total_files = 1;
            }
            $('#voci-progress-bar-text').text('Imported 0/' + total_files + ' files.');
            while( voci_import_next_file( file_number, id, layout, total_files ) === false ) {
                file_number++;
            }
        });
        
    });

    function voci_import_next_file( file_number, content_id, layout, total_files ) {
        $.get(ajaxurl, {action: 'vlc_import_demo', content_id: content_id, layout: layout, file_number: file_number, security: $('.fields-container #demo-nonce').val()}, function (data, status) {

            if ( content_id != 'theme-options' && content_id != 'widgets' && content_id != 'contact' && content_id != 'slider' && content_id != 'newsletter-forms' ) {
                if ( data !== 'done' ) {
                    // Data for progress bar
                    var width = ( 100 / total_files ) * file_number;
                    $('#voci-progress-bar').css('width', width + '%');
                    $('#voci-progress-bar-text').text('Imported ' + file_number + '/' + total_files + ' files.');

                    // add log to field
                    $('.results-field .holder').append( 'Imported ' + file_number + '/' + total_files + ' files.<br><br>' );
                    $('.results-field .holder').append(data);
                    // Scrool log to bottom
                    $('.results-field').scrollTop( $('.results-field .holder').height() );
                    file_number++;
                    voci_import_next_file( file_number, content_id, layout, total_files );
                } else {
                    // enable all buttons
                    $('.table-fields .import-button').prop('disabled', false);
                    $("#" + content_id).closest('td').addClass('imported').removeClass('loading');
                    return false;
                }
            } else {
                // Data for progress bar
                var width = ( 100 / total_files ) * file_number;
                $('#voci-progress-bar').css('width', width + '%');
                $('#voci-progress-bar-text').text('Imported ' + file_number + '/' + total_files + ' files.');
                // add log to field
                $('.results-field .holder').html(data);
                $('.table-fields .import-button').prop('disabled', false);
                // Scrool log to bottom
                $('.results-field').scrollTop( $('.results-field .holder').height() );
                $("#" + content_id).closest('td').addClass('imported').removeClass('loading');
                return false;
            }
        });
    }

});















































// BACKUP CODE


/*jQuery(function ($) {
    $('.table-fields .import-button').on('click', function () {
        var id = $(this).attr('id');
        
        var layout = $("#layout").length > 0 ? $("#layout").val() : false;
        
        // add message to results field
        $('.results-field').html('<p>Please wait while uploading...</p>');

        // add class animate that will start loading animation
        $(this).closest('td').addClass('loading').append($('#circularG'));

        // disable all buttons
        $('.table-fields .import-button').prop('disabled', true);
        
        // ajax request
        $.get(ajaxurl, {action: 'vlc_import_demo', content_id: id, layout: layout, security: $('.fields-container #demo-nonce').val()}, function (data) {
            // add log to field
            $('.results-field').html(data);
            
            // disable all buttons
            $('.table-fields .import-button').prop('disabled', false);
            
            $("#" + id).closest('td').addClass('imported').removeClass('loading');
            
        });

    });

});*/