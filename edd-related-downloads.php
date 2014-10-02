<?php
/*
Plugin Name: Easy Digital Downloads - Related Downloads
Plugin URI: http://isabelcastillo.com/docs/category/easy-digital-downloads-related-downloads-wordpress-plugin
Description: Show related downloads by tag or category when using Easy Digital Downloads plugin.
Version: 1.6.1
Author: Isabel Castillo
Author URI: http://isabelcastillo.com
License: GPL2
Text Domain: easy-digital-downloads-related-downloads
Domain Path: languages

Copyright 2013 - 2014 Isabel Castillo

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
if(!class_exists('Isa_EDD_Related_Downloads')) {
class Isa_EDD_Related_Downloads{

	private static $instance = null;

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'edd_after_download_content', array( $this, 'isa_after_download_content' ), 120 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_filter( 'edd_settings_extensions', array( $this, 'isa_eddrd_add_settings' ) );
		add_action( 'widgets_init', array( $this, 'register_widgets' ) );
		add_filter('plugin_row_meta', array( $this, 'docs_link' ), 10, 2);

		if( ! defined( 'EDDRD_PLUGIN_DIR' ) )
			define( 'EDDRD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		require_once EDDRD_PLUGIN_DIR . 'widget-edd-related.php';
    }

   	function enqueue() {
		if ( is_singular( 'download' ) ) {
            wp_register_style('edd-related-downloads', plugins_url('/edd-related-downloads.css', __FILE__));
            wp_enqueue_style('edd-related-downloads');
		}
	}

	function load_textdomain() {
		load_plugin_textdomain( 'easy-digital-downloads-related-downloads', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Add settings to the "Easy Digital Downloads > Settings > Misc" section
	 * @since 1.0
	 */
	function isa_eddrd_add_settings( $settings ) {
	
		$isa_eddrd_settings = array(
			array(
				'id' => 'isa_eddrd_settings',
				'name' => '<h3 class="title">'. __('Related Downloads Settings', 'easy-digital-downloads-related-downloads') . '</h3>',
				'desc' => __( 'Settings for EDD Related Downloads Plugin.', 'easy-digital-downloads-related-downloads'),
				'type' => 'header'
			),
			array(
				'id' => 'related_filter_by_cat',
				'name' => __('Filter related downloads by category:', 'easy-digital-downloads-related-downloads'), 
				'desc' => __( 'Check this to filter by category. By default, downloads are related by tag.', 'easy-digital-downloads-related-downloads'),
				'type' => 'checkbox'
			),
			array(
				'id' => 'related_showposts_num',
				'name' => __('How many related items to show:', 'easy-digital-downloads-related-downloads'), 
				'desc' => __( 'Enter a decent number, like between 1 and 7. Default is 3.', 'easy-digital-downloads-related-downloads'),
				'type' => 'text',
			),
			array(
				'id' => 'related_dl_title',
				'name' => __('Custom Related Downloads Title:', 'easy-digital-downloads-related-downloads'), 
				'desc' => __( 'This appears above the related items. Default is, "You May Also Like".', 'easy-digital-downloads-related-downloads'),
				'type' => 'text'
			),

			array(
				'id' => 'disable_related_in_content',
				'name' => __('Disable Related Downloads Added To Content:', 'easy-digital-downloads-related-downloads'), 
				'desc' => __( 'Check this to stop the them from being added to the bottom of the single download content. Useful if you are using the sidebar widget instead. Or you could leave this in, set to category, and the widget set to tags, or vice-versa.', 'easy-digital-downloads-related-downloads'),
				'type' => 'checkbox'
			),

array(
				'id' => 'related_dl_orderby',
				'name' => __('Change The Default Method of Sorting (Orderby)', 'easy-digital-downloads-related-downloads'), 
				'desc' => __( 'Choose what the related downloads are sorted by. Default is by "date".', 'easy-digital-downloads-related-downloads'),
				'type' => 'select',
				'options' => array('date' => 'date','ID' => 'ID','author' => 'author','title' => 'title','name' => 'name (post slug)','modified' => 'modified (last modified date)','parent' => 'parent','rand' => 'random','comment_count' => 'comment_count')
			),
array(
				'id' => 'related_dl_order',
				'name' => __('Change The Default Sort Order', 'easy-digital-downloads-related-downloads'), 
				'desc' => __( 'Choose the default sort order for the related downloads. Default is "DESC".', 'easy-digital-downloads-related-downloads'),
				'type' => 'select',
				'options' => array('DESC' => 'DESC','ASC' => 'ASC')
			),
		);
	
		/* Merge plugin settings with original EDD settings */
		return array_merge( $settings, $isa_eddrd_settings );
	}

	/**
	 * Adds related items on the single download page, underneath content
	 * @since 0.1
	 */

	function isa_after_download_content() {
	    global $post, $data, $edd_options;
	// Compatibility fix for EDD Hide Download: save the current download's post id, in order to exclude it later
		$exclude_post_id = $post->ID;
		$taxchoice = isset( $edd_options['related_filter_by_cat'] ) ? 'download_category' : 'download_tag';
		$custom_taxterms = wp_get_object_terms( $post->ID, $taxchoice, array('fields' => 'ids') );

		$howmany = ( 
						isset( $edd_options['related_showposts_num'] ) && 
						! empty( $edd_options['related_showposts_num'] )
					)
					? $edd_options['related_showposts_num'] : 3;

		$related_dl_title = ( 
								isset( $edd_options['related_dl_title'] ) && 
								( '' != $edd_options['related_dl_title'] )
							)
							? $edd_options['related_dl_title'] : __('You May Also Like', 'easy-digital-downloads-related-downloads');

		$loop_orderby = isset( $edd_options['related_dl_orderby'] ) ? $edd_options['related_dl_orderby'] : 'date';

		$loop_order = isset( $edd_options['related_dl_order'] ) ? $edd_options['related_dl_order'] : 'DESC';
		
		if ( ! empty($custom_taxterms) ) {
			$args = array(
					'post_type' => 'download',
					'post__not_in' => array($post->ID),
					'showposts' => $howmany,
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
	 
			$eddrd_query = new WP_Query($args);
			$go = isset( $edd_options['disable_related_in_content'] ) ? '' : 'go';
           
			if( $eddrd_query->have_posts() && $go  ) { ?>
			<div id="isa-related-downloads"><h3><?php echo $related_dl_title; ?></h3><ul>
			<?php while ($eddrd_query->have_posts()) {
					$eddrd_query->the_post();
					if ($post->ID == $exclude_post_id) continue;
					if(has_post_thumbnail()) {
						$thumb = wp_get_attachment_image_src( get_post_thumbnail_id(), apply_filters( 'edd_related_downloads_image_size', 'thumbnail' ) );
						$thumbsrc = is_ssl() ? str_replace( 'http://', 'https://', $thumb[0] ) : $thumb[0];
					}
		            ?>
	                <li>
						<a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>">
							<?php if(has_post_thumbnail()) { ?><img class="wp-post-image" alt="<?php the_title_attribute(); ?>" src="<?php echo apply_filters( 'edd_related_downloads_image_src', $thumbsrc, $post ); ?>" width="<?php echo $thumb[1]; ?>" height="<?php echo $thumb[2]; ?>" /><br /><?php } 

							echo strip_tags( the_title('','', false) ); ?>
						</a>
					</li>
			<?php } ?>
			</ul></div>
			<?php wp_reset_query();
			}
		}
	}
	
	/** 
	 * Registers the EDD Related Downloads Widget.
	 * @since 1.3
	 */
	function register_widgets() {
		register_widget( 'edd_related_downloads_widget' );
	}

	/**
	* Link to Documentation
	* @since 1.4.8
	*/
	function docs_link($links, $file) {
		if ($file == plugin_basename(__FILE__)) {
			$docs_link = '<a href="http://isabelcastillo.com/docs/category/easy-digital-downloads-related-downloads-wordpress-plugin">Docs</a>';
			$links[] = $docs_link;
		}
		return $links;
	}

}
}
$Isa_EDD_Related_Downloads = Isa_EDD_Related_Downloads::get_instance();
