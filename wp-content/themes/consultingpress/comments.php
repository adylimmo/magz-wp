<?php
/**
 * The template for displaying Comments.
 *
 */
?>
<?php if ( post_password_required() ) : ?>
    <p class="nopassword"><?php esc_html_e( 'This post is password protected. Enter the password to view any comments.', 'consultingpress' ); ?></p>

    <?php
    /*
     * Stop the rest of comments.php from being processed,
     * but don't kill the script entirely -- we still have
     * to fully load the template.
     */
    return;


endif; ?>
<?php if ( comments_open() || get_comments_number() ) : ?>
    <!-- .post-comments start -->
    <div class="post-comments">
        <h3><?php echo esc_html__('Comments', 'consultingpress') . '(' . get_comments_number() . ')'; ?></h3>

        <?php if ( have_comments() ) : ?>
            <!-- .comments-li start -->
            <ul class="comments-li">
                <?php
                /*
                 * Loop through and list the comments. 
                 */
                wp_list_comments( array(
                    'callback' => 'Volcanno_Partials::render_comments'
                ) );
                ?>
                <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ): ?>
                    <li class="comments-pagination">
                        <?php paginate_comments_links(); ?>
                    </li>
                <?php endif; ?>
                
            </ul><!--.comments-li end -->
        <?php elseif ( !comments_open() && !is_page() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
            <p class="nocomments"><?php esc_html_e( 'Comments are closed.', 'consultingpress' ); ?></p>
        <?php endif; ?>
    </div><!-- .post-comments end -->
    
    <?php

        $commenter = wp_get_current_commenter();
        $req = get_option( 'require_name_email' );
        $req_text = ( $req ? esc_html__('(required)', 'consultingpress') : '' );
        $aria_req = ( $req ? " aria-required='true'" : '' );

        // Comment form fields
        $comment_form_args = array(
            'comment_field' => 
                '<fieldset class="message">
                    <span class="comment-message-container comment-input-container">
                        <textarea name="comment" class="comment-text" id="comment-message" rows="8" tabindex="1" placeholder="' . esc_html__('Comment', 'consultingpress') . '"></textarea>
                    </span><!-- .comment-message-container.comment-input-container end -->
                </fieldset><!-- .message end -->',
            'fields' => array(
                'author' => 
                    '<fieldset class="name-container">
                        <span class="comment-name-container comment-input-container">
                            <input type="text" name="author" class="name" id="comment-name" tabindex="2" placeholder="' . esc_html__('Name', 'consultingpress') . ' ' . $req_text . '"' . $aria_req . '>
                        </span><!-- .comment-name-container.comment-input-container end -->
                    </fieldset><!-- .name-container end -->',
                'email' => 
                    '<fieldset class="email-container">
                        <span class="comment-email-container comment-input-container">
                            <input type="email" name="email" class="email" id="comment-email" tabindex="3" placeholder="' . esc_html__('Email', 'consultingpress') . ' ' . $req_text . '"' . $aria_req . '>
                        </span><!-- .comment-email-container.comment-input-container end -->
                    </fieldset><!-- .email-container end -->',
                'url' => 
                    '<fieldset class="website-container">
                        <span class="comment-website-container comment-input-container">
                            <input type="url" name="url" class="website" id="comment-website" tabindex="3" placeholder="' . esc_html__('Website (optional)', 'consultingpress') . '">
                        </span><!-- .comment-email-container.comment-input-container end -->
                    </fieldset><!-- .email-container end -->',
            ),
        );

        add_action( 'comment_form_before', function() { echo '<div class="comment-form-container">'; } );
        add_action( 'comment_form_after', function() { echo '</div>'; } );

        comment_form( $comment_form_args ); 

endif; ?>