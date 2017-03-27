<?php

if ( is_attachment() ) {
    return;
} else if ( is_singular( 'post' ) ) {
    // Parent page/category
    $main_category = get_the_category();
    $main_category_id = !empty( $main_category[0]->cat_ID ) ? $main_category[0]->cat_ID : '';
    $parent_id = get_term_meta( $main_category_id, 'parent_blog_page', true ) ? : get_option( 'page_for_posts' );
    // Prev & next post
    $prev_post = get_previous_post( true );
    $next_post = get_next_post( true );
    $back_to = esc_html__( "Back to news", 'consultingpress' );
    $no_prev = esc_html__( "No prev posts", 'consultingpress' );
    $no_next = esc_html__( "No next posts", 'consultingpress' );
} else {
    // Parent page/category
    $main_terms = get_the_terms( get_the_ID(), 'case-studies-category' );
    $main_term_id = is_array( $main_terms ) && !empty( $main_terms[0]->term_id ) ? $main_terms[0]->term_id : '';
    $parent_id = get_term_meta( $main_term_id, 'parent_case_studies_page', true ) ? : get_the_ID();
    // Prev & next post
    $prev_post = get_adjacent_post( true, '', true, 'case-studies-category' );
    $next_post = get_adjacent_post( true, '', false, 'case-studies-category' );
    $back_to = esc_html__( "Back to case studies", 'consultingpress' );
    $no_prev = esc_html__( "No prev case study", 'consultingpress' );
    $no_next = esc_html__( "No next case study", 'consultingpress' );
}

// Get pagination back to permalink
$back_to_permalink = !empty( $parent_id ) ? get_permalink( $parent_id ) : get_home_url();

// Enable or disable pagination
if ( !Volcanno::return_theme_option( 'blog_single_pagination' ) )
    return;
?>

<div class="page-content portfolio-blog-nav-simple">
    <div class="container-fluid">
        <div class="row mb-0">
            <div class="col-md-4">
                <p class="no-posts">
                    <?php if ( !empty( $prev_post ) ): ?>
                        <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="nav-prev"><?php echo esc_html( $prev_post->post_title ); ?></a>
                        <?php
                    else:
                        echo esc_html( $no_prev );
                    endif;
                    ?>
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <a href="<?php echo esc_url( $back_to_permalink ); ?>"><?php echo esc_html( $back_to ); ?></a>
                </p>
            </div>
            <div class="col-md-4">
                <p>
                    <?php if ( !empty( $next_post ) ): ?>
                        <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="nav-next"><?php echo esc_html( $next_post->post_title ); ?></a>
                        <?php
                    else:
                        echo esc_html( $no_next );
                    endif;
                    ?>
                </p>
            </div>
        </div>
    </div>
</div>