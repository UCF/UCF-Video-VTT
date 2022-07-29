<?php
/*
Plugin Name: UCF Video VTT Tools
Description: Plugin that provides a series of tools for developers to properly enqueue descriptive text tracks for videos (vtt files) for their videos.
Version: 0.1.0
Author: UCF Web Communications
License: GPL3
GitHub Plugin URI: UCF/UCF-Video-VTT
*/
namespace UCF\Video_VTT {
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	define( 'UCF_VIDEO_VTT__PLUGIN_FILE', __FILE__ );
	define( 'UCF_VIDEO_VTT__PLUGIN_PATH', dirname( __FILE__ ) );

	require_once UCF_VIDEO_VTT__PLUGIN_PATH . '/includes/common.php';
	require_once UCF_VIDEO_VTT__PLUGIN_PATH . '/includes/api.php';

	// This plugin depends on ACF, so all actions are added after
	// plugins are loaded.
	add_action( 'plugins_loaded', function() {
		add_action( 'acf/init', 'UCF\VIDEO_VTT\Common\add_vtt_fields' );

		// Add mime type filters
		add_filter( 'wp_check_filetype_and_ext', 'UCF\Video_Vtt\Common\add_vtt_extension', 10, 4 );
		add_filter( 'upload_mimes', 'UCF\Video_Vtt\Common\allow_vtt_mime_types' );
	} );

	add_filter( 'rest_pre_serve_request', 'UCF\Video_Vtt\API\maybe_vtt_feed', 10, 4 );
	add_action( 'rest_api_init', 'UCF\Video_Vtt\API\register_rest_routes', 10, 0);

}
