<?php
/**
 * Adds EDD Related Downloads widget
 *
 * @author 		Isabel Castillo
 * @package 	EDD Related Downloads
 * @extends 	WP_Widget
 */

class edd_related_downloads_widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'edd_related_downloads_widget',
			__('EDD Related Downloads Widget', 'edd-related-downloads'),
			array( 'description' => __( 'Display related downloads.', 'edd-related-downloads' ), )
		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		
		$title = apply_filters('widget_title', $instance['title']);
		$number = $instance['number'];
		$taxcat = isset($instance['taxcat']) ? $instance['taxcat'] : false;

		// do we get related by category instead of tag

		$taxchoice = $taxcat ? 'download_category' : 'download_tag';

		echo $before_widget;
		if ( ! empty( $title ) )
			echo '<h3 class="widget-title">'. $title . '</h3>';

if( is_single() && ( 'download' == get_post_type() ) ) {

	    global $post, $data;
		$custom_taxterms = wp_get_object_terms( $post->ID, $taxchoice, array('fields' => 'ids') );

	    $args = array(
				'post_type' => 'download',
	            'post__not_in' => array($post->ID),
	            'showposts' => $number,
				'tax_query' => array(
						array(
							'taxonomy' => $taxchoice,
							'field' => 'id',
							'terms' => $custom_taxterms
						)
					)
	    );
	 


	    $eddrdw_query = new WP_Query($args);
	        if( $eddrdw_query->have_posts() ) {
				?>
				<div id="edd-related-downloads-widget">
				<ul>
				<?php 
	            while ($eddrdw_query->have_posts()) {
	                $eddrdw_query->the_post();

					if(has_post_thumbnail()) {
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
					}
		            ?>
	                <li>
						<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
							<img class="wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo $thumb[0]; ?>" />
							<p><?php the_title(); ?></p>
						</a>
					</li>
          <?php } ?>
          </ul></div>
		<?php wp_reset_query();
			}

} // end here if is single download

		echo $after_widget;

	}// end widget

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['taxcat'] = $new_instance['taxcat'];
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$defaults = array( 
					'title' => 'You May Also Like',
					'number' => 3,
					'taxcat' => false,

					);
 		$instance = wp_parse_args( (array) $instance, $defaults );
    	?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'edd-related-downloads' ); ?></label><input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'How many related downloads to show: ', 'edd-related-downloads' ); ?></label><input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" /></p>

	<p><input id="<?php echo $this->get_field_id( 'taxcat' ); ?>" name="<?php echo $this->get_field_name( 'taxcat' ); ?>" type="checkbox" class="checkbox" <?php checked( $instance['taxcat'], 'on' ); ?> /><label for="<?php echo $this->get_field_id( 'taxcat' ); ?>"><?php _e( ' Filter By Category Instead of Tag', 'edd-related-downloads' ); ?></label></p>

		<?php 
	}

}
?>