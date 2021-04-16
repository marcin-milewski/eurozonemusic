<?php
/*
Plugin Name: Goodlayers Song Post Type
Plugin URI: 
Description: Music player plugin
Version: 1.0.0
Author: Goodlayers
Author URI: http://www.goodlayers.com
License: 
*/
include_once( 'gdlr-song-item.php');	
include_once( 'gdlr-song-option.php');	

// action to loaded the plugin translation file
add_action('plugins_loaded', 'gdlr_song_init');
if( !function_exists('gdlr_song_init') ){
	function gdlr_song_init() {
		load_plugin_textdomain( 'gdlr-song', false, dirname(plugin_basename( __FILE__ ))  . '/languages/' ); 
	}
}

// add action to create song post type
add_action( 'init', 'gdlr_create_song' );
if( !function_exists('gdlr_create_song') ){
	function gdlr_create_song() {
		global $theme_option;
		
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );	
		
		if( !empty($theme_option['song-slug']) ){
			$song_slug = $theme_option['song-slug'];
			$song_category_slug = $theme_option['song-category-slug'];
		}else{
			$song_slug = 'song';
			$song_category_slug = 'album';
		}
		
		register_post_type( 'song',
			array(
				'labels' => array(
					'name'               => __('Songs', 'gdlr-song'),
					'singular_name'      => __('Song', 'gdlr-song'),
					'add_new'            => __('Add New', 'gdlr-song'),
					'add_new_item'       => __('Add New Song', 'gdlr-song'),
					'edit_item'          => __('Edit Song', 'gdlr-song'),
					'new_item'           => __('New Song', 'gdlr-song'),
					'all_items'          => __('All Songs', 'gdlr-song'),
					'view_item'          => __('-', 'gdlr-song'),
					'search_items'       => __('Search Song', 'gdlr-song'),
					'not_found'          => __('No songs found', 'gdlr-song'),
					'not_found_in_trash' => __('No songs found in Trash', 'gdlr-song'),
					'parent_item_colon'  => '',
					'menu_name'          => __('Songs', 'gdlr-song')
				),
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $song_slug  ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array( 'title', 'custom-fields' )
			)
		);
		
		// create song categories
		register_taxonomy(
			'album', array("song"), array(
				'hierarchical' => true,
				'show_admin_column' => true,
				'labels' => array(
					'name'              => __( 'Album', 'gdlr-song' ),
					'singular_name'     => __( 'Album', 'gdlr-song' ),
					'search_items'      => __( 'Search Albums', 'gdlr-song' ),
					'all_items'         => __( 'All Albums', 'gdlr-song' ),
					'parent_item'       => __( 'Parent Album', 'gdlr-song' ),
					'parent_item_colon' => __( 'Parent Album:', 'gdlr-song' ),
					'edit_item'         => __( 'Edit Album', 'gdlr-song' ),
					'update_item'       => __( 'Update Album', 'gdlr-song' ),
					'add_new_item'      => __( 'Add New Album', 'gdlr-song' ),
					'new_item_name'     => __( 'New Album Name', 'gdlr-song' ),
					'menu_name'         => __( 'Album', 'gdlr-song' ),
				), 
				'singular_label' => __('Album', 'gdlr-song'), 
				'rewrite' => array( 'slug' => $song_category_slug  )));
		register_taxonomy_for_object_type('album', 'song');
		
				if( is_admin() && class_exists('gdlr_tax_meta') ){
			global $gdlr_sidebar_controller;
			
			new gdlr_tax_meta( 
				array(
					'taxonomy'=>'album',
					'slug'=>'gdlr_album_meta'
				),
				array(
					'upload' => array(
						'title'=> __('Album Thumbnail', 'gdlr-song'),
						'type'=> 'upload'
					),
					'content' => array(
						'title'=> __('Album Content', 'gdlr-song'),
						'type'=> 'textarea'
					),
					'composer' => array(
						'title'=> __('Composer', 'gdlr-song'),
						'type'=> 'text'
					),
					
		// added additional fields: genre, description, release date & download links			
					
					'genre' => array(
						'title'=> __('Genre', 'gdlr-song'),
						'type'=> 'text'
					),
						'description' => array(
						'title'=> __('Description', 'gdlr-song'),
						'type'=> 'text'
					),
					'date' => array(
						'title'=> __('Release Date', 'gdlr-song'),
						'type'=> 'date-picker'
					),
					'download-link' => array(
						'title'=> __('Download Link', 'gdlr-song'),
						'type'=> 'text'
					),
					'apple-link' => array(
						'title'=> __('Apple Link', 'gdlr-song'),
						'type'=> 'text'
					),
					'amazon-link' => array(
						'title'=> __('Amazon Link', 'gdlr-song'),
						'type'=> 'text'
					),
					'sidebar' => array(
						'title'=> __('Sidebar Type', 'gdlr-song'),
						'type'=> 'combobox',
						'options'=> array(
							'no-sidebar' => __('Without Sidebar', 'gdlr-song'),
							'left-sidebar' => __('Left Sidebar', 'gdlr-song'),
							'right-sidebar' => __('Right Sidebar', 'gdlr-song'),
							'both-sidebar' => __('Both Sidebar', 'gdlr-song'),
						)
					),
					'left-sidebar' => array(
						'title'=> __('Left Sidebar ( if selected )', 'gdlr-song'),
						'type'=> 'combobox',
						'options'=> $gdlr_sidebar_controller->get_sidebar_array()
					),
					'right-sidebar' => array(
						'title'=> __('Right Sidebar ( if selected )', 'gdlr-song'),
						'type'=> 'combobox',
						'options'=> $gdlr_sidebar_controller->get_sidebar_array()
					)
				)
			);
		}		
		
		// add filter to style single template
		if( defined('WP_THEME_KEY') && WP_THEME_KEY == 'goodlayers' ){
			add_action('pre_get_posts', 'gdlr_redirect_song_404');
			add_filter('archive_template', 'gdlr_register_archive_template');
		}
	}
}

if( !function_exists('gdlr_redirect_song_404') ){
	function gdlr_redirect_song_404( $query ) {
		if( $query->is_main_query() && $query->is_single() ){
			$args = $query->query;
			if( !empty($args['post_type']) && $args['post_type'] == 'song' ){
				$query->is_404 = true;
			}
		}
	}
}

if( !function_exists('gdlr_register_archive_template') ){
	function gdlr_register_archive_template( $archive_template ) {
		
		if( is_tax('album') ){	
			$archive_template = dirname( __FILE__ ) . '/single-album.php';
		}
		
		return $archive_template;
	}
}

?>
