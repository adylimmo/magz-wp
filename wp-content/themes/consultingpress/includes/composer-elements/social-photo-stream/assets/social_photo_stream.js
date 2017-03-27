(jQuery)(function ($) {
    /* ================ SOCIAL NETWORK FEED ================ */
    $('.social-feed').each(function () {
        var network = $(this).data('network');
        var username = $(this).data('username');
        var limit = $(this).data('limit');
        var apiKey = $(this).data('api');

        $(this).socialstream({
            socialnetwork: network,
            username: username,
            limit: limit,
            apikey: apiKey
        });
    });


});