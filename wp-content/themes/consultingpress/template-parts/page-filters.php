<?php 

$all_terms = get_terms( array( 'taxonomy' => 'case-studies-category' ) );
// Current term
$term = get_query_var('term');
$term_object = get_term_by('slug', $term, 'case-studies-category');
$term_id = !empty($term_object) ? $term_object->term_id : '';
$current_parent = get_term_meta( $term_id, 'parent_case_studies_page', true ) ?: get_the_ID();

// Disabled parent filter
$disabled_parent = apply_filters( 'consultingpress_volcanno_disabled_parent_filter', false );

// Display page filters
if ( apply_filters( 'consultingpress_volcanno_disable_page_filters', false ) ) return;
if ( !rwmb_meta('pg_case_studies_category_filter', '', $current_parent ) ) return;

// Current parent filter
$current_parent = apply_filters( 'consultingpress_volcanno_page_filters_current_parent', $current_parent );

$all = get_permalink( $current_parent );

// All terms
$active = !is_tax() ? ' is-checked' : '';
$filters_output = '<li class="filter-button' . $active . '"><a href="' . esc_url($all) . '">' . esc_html__('All', 'consultingpress') . '</a></li>';

foreach ($all_terms as $index => $term) {
    $term_id = $term->term_id;
    $term_parent = get_term_meta( $term_id, 'parent_case_studies_page', true );
    $active = is_tax( 'case-studies-category', $term_id ) ? ' is-checked' : '';
    if ( $term->parent != 0 || !$disabled_parent ) {
        if ( $term_parent == $current_parent ) {
            $term_url = get_term_link( $term_id, 'case-studies-category' );
            $filters_output .= '<li class="filter-button' . $active . '"><a href="' . esc_url($term_url) . '">' . $term->name . '</a></li>';
        }
    }

}

?>

<!-- .row start -->
<div class="row portfolio-filters">

    <!-- .col-md-12 start -->
    <div class="col-md-12">
        <ul id="filters" class="button-group filters-button-group clearfix">
            <?php echo $filters_output; ?>
        </ul><!-- #filters.clearfix end -->
    </div><!-- .col-md-12 end -->
</div><!-- .row end -->