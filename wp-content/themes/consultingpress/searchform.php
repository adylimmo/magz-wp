<?php
/**
 * The template for displaying search form in theme.
 *
 */
$search_placeholder = Volcanno::return_theme_option('search_placeholder') ?: esc_attr__('Type and hit enter...', 'consultingpress');
?>
<!-- search start -->
<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get">
    <input class="a_search" name="s" type="text" placeholder="<?php echo esc_attr( $search_placeholder ); ?>" onkeydown="<?php echo esc_js( 'if (event.keyCode == 13) { this.form.submit(); return false; }' ) ?>"/>
    <input class="search-submit" type="submit">
</form>