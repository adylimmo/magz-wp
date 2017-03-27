/* 
 * PORTFOLIO FILTER - REQUIRED ON PORTFOLIO PAGES WITH PROJECT FILTERS
 */

jQuery(document).ready(function ($) {
    'use strict';

    // ISOTOPE PORTFOLIO GRID
    $(function () {
        // init Isotope
        var $grid = $('#portfolioitems').isotope({
            itemSelector: '.isotope-item',
            percentPosition: true

        });
        // layout Isotope after each image loads
        $grid.imagesLoaded().progress(function () {
            $grid.isotope('layout');
        });

        // filter functions
        var filterFns = {
        };
        // bind filter button click
        $('.filters-button-group').on('click', '.filter-button', function () {
            var filterValue = $(this).attr('data-filter');
            // use filterFn if matches value
            filterValue = filterFns[ filterValue ] || filterValue;
            $grid.isotope({filter: filterValue});
        });
        // change is-checked class on buttons
        $('.button-group').each(function (i, buttonGroup) {
            var $buttonGroup = $(buttonGroup);
            $buttonGroup.on('click', '.filter-button', function () {
                $buttonGroup.find('.is-checked').removeClass('is-checked');
                $(this).addClass('is-checked');
            });
        });
    });
});