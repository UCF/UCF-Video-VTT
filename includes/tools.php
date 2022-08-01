<?php
/**
 * All public facing, documented tools should be in here
 */
namespace UCF\Video_Vtt\Tools {
	/**
	 * Determines if an attachment has a valid description
	 * or vtt file to provide for a video
	 *
	 * @author Jim Barnes
	 * @since  0.1.0
	 * @param  int $attachment_id
	 * @return bool
	 */
	function video_has_description( $attachment_id ) {
		$retval = false;
		$media = get_post( $attachment_id );
		$file = get_field( 'vtt_file', $media->ID );

		if ( ! empty( $media ) ) $retval = true;
		if ( ! empty( $file ) ) $retval = true;

		return $retval;
	}

	/**
	 * Returns the fully qualified endpoint URL
	 *
	 * @author Jim Barnes
	 * @since  0.1.0
	 * @return string
	 */
	function get_api_endpoint_url() {
		return \get_rest_url( null, '/ucf-video-vtt/v1' );
	}

	/**
	 * Generates the fully qualified endpoint URL for a
	 * specific piece of media.
	 *
	 * @author Jim Barnes
	 * @since  0.1.0
	 * @param  int $media_id
	 * @return string
	 */
	function get_vtt_api_endpoint( $media_id ) {
		return get_api_endpoint_url() . "/vtt/$media_id";
	}

	/**
	 * Retrieves the track markup for a given video
	 *
	 * @author Jim Barnes
	 * @since  0.1.0
	 * @param  int|string $media_id The ID or URL of the video attachment
	 * @return void
	 */
	function get_track_markup( $media_id ) {
		if ( ! is_int( $media_id ) ) {
			$media_id = attachment_url_to_postid( $media_id );
		}

		ob_start();

		if (
			is_int( $media_id ) &&
			video_has_description( $media_id )
		) :
			$api_url = get_vtt_api_endpoint( $media_id );
		?>
			<track label="Descriptive Text" kind="descriptions" src="<?php echo $api_url; ?>" srclang="en" default />
		<?php

		endif;

		return ob_get_clean();
	}
}
