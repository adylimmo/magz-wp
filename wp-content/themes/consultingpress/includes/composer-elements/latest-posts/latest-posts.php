<?php
/**
 * Visual composer mapping
 */
add_action( 'vc_before_init', 'volcanno_latest_posts_integrateWithVC' );
if ( !function_exists('volcanno_latest_posts_integrateWithVC') ) {
   function volcanno_latest_posts_integrateWithVC() {
      vc_map( array(
         "name" => esc_html__( "Latest posts", 'consultingpress' ),
         "base" => "volcanno_latest_posts",
         "class" => "",
         "icon" => get_template_directory_uri() . "/includes/composer-elements/assets/icon-volcanno.png",
         "category" => esc_html__( "Consulting Press", 'consultingpress'),
         "params" => array(
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Style", 'consultingpress' ),
               "param_name" => "style",
               "description" => esc_html__( "Here you can select how to display posts.", 'consultingpress' ),
               "value" => array(
                  'Carousel' => 'carousel',
                  'Image Boxes' => 'img-box',
                  'Small Image' => 'img-small',
                  'Simple list' => 'simple-list',
               ),
            ),
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Show posts by:", 'consultingpress' ),
               "param_name" => "posts_source",
               "description" => esc_html__( "Here you can select wich posts to show.", 'consultingpress' ),
               "value" => array(
                  'Category' => 'category',
                  'Post ID' => 'post-id',
               ),
            ),
            // IF CATEGORY
            array(
               "type" => "dropdown",
               "class" => "",
               "heading" => esc_html__( "Category", 'consultingpress' ),
               "param_name" => "category",
               "description" => esc_html__( "Here you can select wich posts to show.", 'consultingpress' ),
               "value" => Volcanno_Visual_Composer::vc_get_categories(),
               "std" => 'category',
               "dependency" => array(
                  'element' => 'posts_source',
                  'value' => 'category',
               ),
               "admin_label" => true,
            ),
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Exclude posts", 'consultingpress' ),
               "param_name" => "exclude",
               "value" => "",
               "description" => esc_html__( "Here you can exclude posts from category by post id.", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'posts_source',
                  'value' => 'category',
               ),
            ),
            // IF POST ID
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Post IDs", 'consultingpress' ),
               "param_name" => "posts_id",
               "value" => "",
               "description" => esc_html__( "Enter posts IDs to display only those records ( Note: separate values by commas (,) ).", 'consultingpress' ),
               "dependency" => array(
                  'element' => 'posts_source',
                  'value' => 'post-id',
               ),
               "admin_label" => true,
            ),
            // GENERAL
            array(
               "type" => "textfield",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Number of posts", 'consultingpress' ),
               "param_name" => "posts_count",
               "value" => esc_html__( "9", 'consultingpress' ),
               "description" => esc_html__( "Here you can enter how many posts to show.", 'consultingpress' ),
            ),
            array(
               "type" => "checkbox",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Hide date", 'consultingpress' ),
               "param_name" => "hide_date",
               "value" => "",
               "description" => esc_html__( "Here you can select to hide post date.", 'consultingpress' ),
            ),
            array(
               "type" => "checkbox",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Hide post thumbnail", 'consultingpress' ),
               "param_name" => "hide_thumbnail",
               "value" => "",
               "description" => esc_html__( "Here you can select to hide post thumbnail.", 'consultingpress' ),
            ),
            array(
               "type" => "checkbox",
               "holder" => "",
               "class" => "",
               "heading" => esc_html__( "Hide read more", 'consultingpress' ),
               "param_name" => "hide_read_more",
               "value" => "",
               "description" => esc_html__( "Here you can select to hide read more button.", 'consultingpress' ),
            ),
            Volcanno_Visual_Composer::animation_param(),
            Volcanno_Visual_Composer::extra_class_param(),
            Volcanno_Visual_Composer::design_param(),
         )
      ) );
   }
}

/**
 * Wordpress shortcode
 * 
 * @param  $atts
 * @return string
 */
if ( !function_exists('volcanno_latest_posts_func') ) {
   function volcanno_latest_posts_func( $atts ) {
      extract(shortcode_atts( array(
         'style' => 'carousel',
         'posts_source' => 'category',
         'category' => '',
         'exclude' => '',
         'posts_id' => '',
         'posts_count' => '9',
         'hide_date' => '',
         'hide_thumbnail' => '',
         'hide_read_more' => '',
         'animation_param' => '',
         'extra_class_param' => '',
         'design_param' => '',
      ), $atts ));

      // Animation, Design & Extra Class params
      $css_class = Volcanno_Visual_Composer::return_css_class( $design_param, $animation_param, $extra_class_param );
      $data_animate = !empty( $animation_param ) ? ' data-animate="' . $animation_param . '"' : '';

      if ( $style == 'carousel' ) {
         // Enqueue styles
         wp_enqueue_style( 'owl-carousel' );
         // Enqueue scripts
         wp_enqueue_script( 'owl-carousel' );
         // Initialize owl carousel
         wp_enqueue_script( 'owl-carousel-init' );
      
      }

      // The WordPress Query.
      $args = array( 'posts_per_page' => $posts_count );

      if ( $posts_source == 'category' ) {
         if ( $category != '' ) {
            $args['category_name'] = $category;
         }
         if ( $exclude != '' ) {
            $exclude = explode(',', $exclude);
            $args['post__not_in'] = $exclude;
         }
      } else {
         if ( $posts_id != '' ) {
            $posts_id = explode(',', $posts_id);
            $args['post__in'] = $posts_id;
         }
      }

      $latest_posts = new WP_Query( $args );
      
      if ( $latest_posts->have_posts() ) :
         
         // Initial content variable
         $output_content = '';

         while ( $latest_posts->have_posts() ) : $latest_posts->the_post();
            
            // Read more link
            $read_more = !$hide_read_more ? '<a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html__('Read more', 'consultingpress') . '</a>' : '';
            // Date
            $date = !$hide_date ? '<span class="date">' . get_the_date() . '</span>' : '';
            // Image tag
            $image = !$hide_thumbnail ? Volcanno_Visual_Composer::get_image( get_the_ID(), array(360, 245), true, 'retina', true ) : '';
            $post_thumbnail = !$hide_thumbnail ? '<div class="post-media"><a href="' . esc_url( get_permalink() ) . '" title="' . esc_html( get_the_title() ) . '">' . $image . '</a></div>' : '';

            if ( $style == 'carousel' ) :

               $output_content .=   '<div class="owl-item">
                                       <div class="post-container clearfix">
                                          ' . $post_thumbnail . '
                                          <div class="post-body">
                                             ' . $date . '
                                             <a href="' . esc_url( get_permalink() ) . '">
                                                ' . the_title( '<h3>', '</h3>', false ) . '
                                             </a>
                                             ' . $read_more . '  
                                          </div>
                                       </div>
                                    </div>';

            elseif ( $style == 'img-box' ) :

               $output_content .=   '<li class="post-container clearfix">
                                       ' . $post_thumbnail . '
                                       <div class="post-body">
                                          ' . $date . '
                                          <a href="' . esc_url( get_permalink() ) . '">
                                             ' . the_title( '<h3>', '</h3>', false ) . '
                                          </a>
                                          ' . $read_more . '
                                       </div>
                                    </li>';

            elseif ( $style == 'img-small' ) :

               $output_content .=   '<li class="post-container clearfix">
                                       ' . $post_thumbnail . '
                                       <div class="post-body">
                                          ' . $date . '
                                          <a href="' . esc_url( get_permalink() ) . '">
                                             ' . the_title( '<h4>', '</h4>', false ) . '
                                          </a>
                                       </div>
                                     </li>';

            elseif ( $style == 'simple-list' ) :

               $output_content .=   '<li class="post-container">
                                       <a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>
                                       ' . $date . '
                                    </li>';

            endif;

         endwhile;

         // Parent wrapper
         $output = $style == 'carousel' ? '<div class="carousel-container' . $css_class . '"' . $data_animate . '><div class="owl-carousel latest-posts-carousel pi-latest-posts-01" data-type="latest-posts-carousel">' . $output_content . '</div></div>' : '';
         $output = $style == 'img-box' ? '<ul class="pi-latest-posts-01 clearfix' . $css_class . '"' . $data_animate . '>' . $output_content . '</ul>' : $output;
         $output = $style == 'img-small' ? '<ul class="pi-latest-posts-02 clearfix' . $css_class . '"' . $data_animate . '>' . $output_content . '</ul>' : $output;
         $output = $style == 'simple-list' ? '<ul class="pi-latest-posts-03' . $css_class . '"' . $data_animate . '>' . $output_content . '</ul>' : $output;
      
      endif;

      wp_reset_postdata();

      return isset( $output ) ? $output : '';
   }
}