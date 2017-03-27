<?php

$author_description = get_the_author_meta( 'description' );

if ( Volcanno::return_theme_option( 'blog_single_author_description' ) && !empty( $author_description ) ) : ?>

    <div class="blog-single-author clearfix">
        <div class="avatar-container">
        <?php echo get_avatar( get_the_id() ); ?>
        </div><!-- .avatar end -->
        <div class="text-container">
            <h3><?php the_author_meta( 'display_name' ); ?></h3>
            <p><?php the_author_meta( 'description' ); ?></p>
        </div><!-- .text-container end -->
    </div><!-- .blog-single-author end -->

<?php endif; ?>
