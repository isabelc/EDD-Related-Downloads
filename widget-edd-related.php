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
			__('EDD Related Downloads by Isa', 'easy-digital-downloads-related-downloads'),
			array( 'description' => __( 'Display related downloads.', 'easy-digital-downloads-related-downloads' ), )
		);
	}
	/**
	 * Front-end display of widget.
	 */
	public function widget( $args, $instance ) {

		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'You May Also Like', 'easy-digital-downloads-related-downloads' ) : $instance['title'], $instance, $this->id_base );
		$number = isset( $instance['number'] ) ? $instance['number'] : 3;
		$taxcat = isset($instance['taxcat']) ? $instance['taxcat'] : false;

		// do we get related by category instead of tag
		$taxchoice = $taxcat ? 'download_category' : 'download_tag';
		
		echo $args['before_widget'];
		
		global $post, $data, $edd_options;
		if( is_single() && ( 'download' == get_post_type() ) ) {
			// Compatibility fix for EDD Hide Download: save the current download's post id, in order to exclude it later
			$exclude_post_id = $post->ID;
			$custom_taxterms = wp_get_object_terms( $post->ID, $taxchoice, array('fields' => 'ids') );

			$loop_orderby = isset( $edd_options['related_dl_orderby'] ) ? $edd_options['related_dl_orderby'] : 'date';
			$loop_order = isset( $edd_options['related_dl_order'] ) ? $edd_options['related_dl_order'] : 'DESC';

			if ( ! empty($custom_taxterms) ) {
				$query_args = array(
						'post_type' => 'download',
						'post__not_in' => array($post->ID),
						'showposts' => $number,
						'tax_query' => array(
								array(
									'taxonomy' => $taxchoice,
									'field' => 'id',
									'terms' => $custom_taxterms
								)
							),
						'orderby' => $loop_orderby,
						'order' => $loop_order
				);
			
				$eddrdw_query = new WP_Query($query_args);
				if( $eddrdw_query->have_posts() ) {

					if ( $title ) {
						echo '<h3 class="widget-title">'. $title . '</h3>';
					} ?>
					<div id="edd-related-downloads-widget">
					<ul>
					<?php 
					while ($eddrdw_query->have_posts()) {
						$eddrdw_query->the_post();
						if ($post->ID == $exclude_post_id)
							continue;
						if(has_post_thumbnail()) {
							$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), apply_filters( 'edd_related_downloads_image_size', 'thumbnail' ) );
							$thumbsrc = is_ssl() ? str_replace( 'http://', 'https://', $thumb[0] ) : $thumb[0];
						} ?>
						<li>
							<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
								<?php if(has_post_thumbnail()) { ?><img class="wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo apply_filters( 'edd_related_downloads_image_src', $thumbsrc, $post ); ?>" width="<?php echo $thumb[1]; ?>" height="<?php echo $thumb[2]; ?>" /><?php } 
								if ( ! isset( $edd_options['related_dl_only_image'] ) ) { ?>
									<p><?php echo strip_tags( the_title('','', false) ); ?></p>
								<?php }// @test  ?>
							</a>
						</li>
					<?php } ?>
					</ul></div>
					<?php wp_reset_query();
				}
			}
		} // end if is single download

		echo $args['after_widget'];
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['taxcat'] = isset( $new_instance['taxcat'] ) ? $new_instance['taxcat'] : false;
		return $instance;
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {
		$defaults = array( 
				'title' => __('You May Also Like','easy-digital-downloads-related-downloads'),
				'number' => 3,
				'taxcat' => false);
 		$instance = wp_parse_args( (array) $instance, $defaults );
    	?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'easy-digital-downloads-related-downloads' ); ?></label><input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" /></p>
		<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'How many related downloads to show: ', 'easy-digital-downloads-related-downloads' ); ?></label><input type="text" class="widefat" id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" value="<?php echo $instance['number']; ?>" /></p>
		<p><input id="<?php echo $this->get_field_id( 'taxcat' ); ?>" name="<?php echo $this->get_field_name( 'taxcat' ); ?>" type="checkbox" class="checkbox" <?php checked( $instance['taxcat'], 'on' ); ?> /><label for="<?php echo $this->get_field_id( 'taxcat' ); ?>"><?php _e( ' Filter By Category Instead of Tag', 'easy-digital-downloads-related-downloads' ); ?></label></p>
		<?php 
	}
}
?>