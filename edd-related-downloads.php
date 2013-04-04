<?php
/*
Plugin Name: EDD Related Downloads
Plugin URI: http://isabelcastillo.com/edd-related-downloads-plugin/
Description: Show related downloads by tag or category.
Version: 1.0
Author: Isabel Castillo
Author URI: http://isabelcastillo.com
License: GPL2
Text Domain: edd-related-downloads
Domain Path: languages

Copyright 2013 Isabel Castillo

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if(!class_exists('EDD_Related_Downloads')) {
class EDD_Related_Downloads{
    public function __construct() {

		add_action( 'edd_after_download_content', array( $this, 'isa_after_download_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	    add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_filter( 'edd_settings_misc', array( $this, 'isa_eddrd_add_settings' ) );


    }

   	public function enqueue() {
			
		if ( is_singular( 'download' ) ) {
	            wp_register_style('edd-related-downloads', plugins_url('/edd-related-downloads.css', __FILE__));
	            wp_enqueue_style('edd-related-downloads');
		}
	}


	public function load_textdomain() {

		load_plugin_textdomain( 'edd-related-downloads', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	}

	/**
	 * Adds 1 setting to the "Easy Digital Downloads > Settings > Misc" section
	 *
	 * @since 1.0
	 */
	function isa_eddrd_add_settings( $settings ) {
	
		$isa_eddrd_settings = array(
			array(
				'id' => 'isa_eddrd_settings',
				'name' => '<strong>'. __('Related Downloads Filter', 'edd-related-downloads') . '</strong>',
				'desc' => __( 'Filter related downloads by category.', 'edd-related-downloads'),
				'type' => 'header'
			),
			array(
				'id' => 'related_filter_by_cat',
				'name' => __('Filter By Category:', 'edd-related-downloads'), 
				'desc' => __( 'Check this to filter by category. By default, downloads are related by tag.', 'edd-related-downloads'),
				'type' => 'checkbox'
			)
		);
	
		/* Merge plugin settings with original EDD settings */
		return array_merge( $settings, $isa_eddrd_settings );

	}

	/**
	 * Adds related items on the single download page, underneath content
	 *
	 * @since 0.1
	 */

	public 	function isa_after_download_content() {
	    global $post, $data, $edd_options;
		$taxchoice = isset( $edd_options['related_filter_by_cat'] ) ? 'download_category' : 'download_tag';
		$custom_taxterms = wp_get_object_terms( $post->ID, $taxchoice, array('fields' => 'ids') );
	    $args = array(
				'post_type' => 'download',
	            'post__not_in' => array($post->ID),
	            'showposts' => 3,
				'tax_query' => array(
						array(
							'taxonomy' => $taxchoice,
							'field' => 'id',
							'terms' => $custom_taxterms
						)
					)
	    );
	 
	    $eddrd_query = new WP_Query($args);
	        if( $eddrd_query->have_posts() ) {
	           	$custom_text = __('Related', 'edd-related-downloads'); // YOU can edit this
	            echo '<div id="isa-related-downloads"><h3>'.$custom_text.'</h3><ul>';
	            while ($eddrd_query->have_posts()) {
	                $eddrd_query->the_post();

			if(has_post_thumbnail()) {
			$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), 'thumbnail' );
			}
	            ?>
	                <li>
						<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
							<img class="wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo $thumb[0]; ?>" />
							<?php the_title(); ?>
						</a>
					</li>
	            <?php
	            }
	            echo '</ul></div>';
	            wp_reset_query();
	        }
	}
}
}
$EDD_Related_Downloads = new EDD_Related_Downloads();