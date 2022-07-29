<?php

namespace UCF\VIDEO_VTT\Common {
	/**
	 * Adds the VTT File field to video attachments
	 *
	 * @author Jim Barnes
	 * @since 0.1.0
	 * @return void
	 */
	function add_vtt_fields() {
		acf_add_local_field_group( array(
			'key' => 'vtt_files_fg',
			'title' => 'VTT Files',
			'fields' => array(
				array(
					'key' => 'vtt_file',
					'label' => 'VTT File',
					'name' => 'vtt_file',
					'type' => 'file',
					'instructions' => 'Upload a vtt file for the video.',
					'return_format' => 'url',
					'library' => 'all',
					'mime_types' => '.vtt',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'attachment',
						'operator' => '==',
						'value' => 'video',
					),
				),
			)
		));
	}

	/**
	 * Ensures vtt files have the right MIME type
	 *
	 * @author Jim Barnes
	 * @since  0.1.0
	 * @param  array $types The type information of the file
	 * @param  string $file The full path to the file
	 * @param  string $filename The filename
	 * @param  string[] $mimes Array of mime types keyed by their file extension regex
	 * @return array
	 */
	function add_vtt_extension( $types, $file, $filename, $mimes ) {
		if ( false !== strpos( $filename, '.vtt' ) ) {
			$types['ext'] = 'vtt';
			$types['type'] = 'text/vtt';
		}

		return $types;
	}

	/**
	 * Adds the vtt MIME type to the $mimes array.
	 * This will allow this file type to be uploaded.
	 *
	 * @author Jim Barnes
	 * @since 0.1.0
	 * @param  array $mimes Array of mime types keyed by their file extension regex
	 * @return array The modified $mimes array
	 */
	function allow_vtt_mime_types( $mimes ) {
		$mimes['vtt'] = 'text/vtt';
		return $mimes;
	}

}
