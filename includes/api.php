<?php
/**
 * Rest API endpoint for retrieving video vtt files
 */
namespace UCF\VIDEO_VTT\API {
	/**
	 * The route handler for getting vtt files
	 *
	 * @author Jim Barnes
	 * @since 0.1.0
	 * @param  array $data The array of parameters
	 * @return string
	 */
	function get_video_vtt_file( $data ) {
		$video_id = $data['id'] ?? null;
		$media = get_post( $video_id );
		$maybe_vtt = get_field( 'vtt_file', $media->ID );

		$retval = "";

		if ( $maybe_vtt ) {
			$response = wp_remote_get( $maybe_vtt, array( 'timeout' => 5 ) );
			$retval = wp_remote_retrieve_body( $response );
		} else {
			$video_meta = get_post_meta( $video_id, '_wp_attachment_metadata', true );

			$start_time = \DateTime::createFromFormat( "i:s.v", "00:00.000" );
			$end_time = \DateTime::createFromFormat( "i:s", str_pad( $video_meta['length_formatted'], 5, "0", STR_PAD_LEFT ) );

			ob_start();
?>
WEBVTT

<?php echo $start_time->format('i:s.v'); ?> --> <?php echo $end_time->format('i:s.v'); ?>

<?php echo $media->post_content; ?>
<?php
			$retval = ob_get_clean();
		}

		return $retval;
	}

	/**
	 * Determines how the get_video_vtt_file gets served up.
	 * Since this is not a JSON response, we have to interrupt the
	 * serving process to ensure it gets served up as a .vtt file.
	 *
	 * @author Jim Barnes
	 * @since  0.1.0
	 * @param  bool $served Whether the request has been served or not
	 * @param  WP_HTTP_Response $result The response object
	 * @param  WP_REST_Request $request The request object
	 * @param  WP_REST_Server $server The server object (used for sending headers)
	 * @return void
	 */
	function maybe_vtt_feed( $served, $result, $request, $server ) {
		if ( $request->get_attributes()['callback'] !== __NAMESPACE__ . '\get_video_vtt_file' ) {
			return $served;
		}

		$server->send_header( 'Content-Type', 'text/vtt' );
		echo $result->get_data();

		exit;
	}

	/**
	 * Registers the rest routes for the API
	 *
	 * @author Jim Barnes
	 * @since  0.1.0
	 * @return void
	 */
	function register_rest_routes() {
		register_rest_route( 'ucf-video-vtt/v1', 'vtt/(?P<id>\d+)', array(
			'methods'              => 'GET',
			'callback'             => __NAMESPACE__ . '\get_video_vtt_file',
			'permissions_callback' => '__return_true'
		) );
	}
}
