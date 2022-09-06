<?php
/**
 * The template for displaying search forms
 *
 * @package Hellish Simplicity
 * @since Hellish Simplicity 1.1
 */
?>
<form method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="s" class="assistive-text"><?php _e( 'Search', 'hellish' ); ?></label>
	<input type="text" id="s" class="field" name="s" placeholder="<?php esc_attr_e( 'Search', 'hellish' ); ?>" />
	<input type="submit" class="submit" name="submit" value="<?php esc_attr_e( 'Search', 'hellish' ); ?>" />
</form>
