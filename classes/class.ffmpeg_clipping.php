<?php
/**
 * Class FFMpeg_Clipping
 */
Class FFMpeg_Clipping {

	/**
	 * @var $uploader
	 */
	private $uploader;

	/**
	 * @var $ffmpeg
	 */
	private $ffmpeg;

	/**
	 * FFMpeg_Clipping constructor.
	 */
	public function __construct( $uploader, $ffmpeg ) {
		$this->uploader = $uploader;
		$this->ffmpeg   = $ffmpeg;
	}

	public function upload_xml( $xml_file ){

		$xml = false;

		$metadata = $this->uploader->upload( $xml_file, array(
			'limit' => 1, //Maximum Limit of files. {null, Number}
			'maxSize' => 10, //Maximum Size of files {null, Number(in MB's)}
			'extensions' => array('xml'), //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
			'required' => true, //Minimum one file is required for upload {Boolean}
			'uploadDir' => 'uploads/', //Upload directory {String}
			'title' => array('{{file_name}}'), //-{{random}}{{timestamp}}-New file name {null, String, Array} *please read documentation in README.md
			'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
			'replace' => true, //Replace the file if it already exists  {Boolean}
			'perms' => null, //Uploaded file permisions {null, Number}
			'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
			'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
			'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
			'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
			'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
			'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
		));

		if( $metadata['isComplete'] ) {

			$xml_metas  = $metadata['data'];
			$xml_file   = $xml_metas['metas'][0]['file'];
			$xml        = simplexml_load_file( $xml_file );
		}
		return $xml;
	}

	public function upload_video( $video_file ){

		$video = false;
		$video_files = $this->uploader->upload( $video_file, array(
			'limit' => 1, //Maximum Limit of files. {null, Number}
			//'maxSize' => null, //Maximum Size of files {null, Number(in MB's)}
			'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
			'required' => false, //Minimum one file is required for upload {Boolean}
			'uploadDir' => 'uploads/', //Upload directory {String}
			'title' => array('{{file_name}}'), //-{{random}}{{timestamp}}- New file name {null, String, Array} *please read documentation in README.md
			'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
			'replace' => true, //Replace the file if it already exists  {Boolean}
			'perms' => null, //Uploaded file permisions {null, Number}
			'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
			'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
			'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
			'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
			'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
			'onRemove' => null //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
		));

		if( $video_files['isComplete'] ) {
			$video = $video_files['data'];
		}
		return $video;
	}

	public function video_clipping( $xml_file, $video_file ){
		$xml    = $this->upload_xml( $xml_file );
		$_video = $this->upload_video( $video_file );
		$video  = $_video['metas'][0];
		if ( $xml !== false ){
			foreach ($xml as $meta_data){

				$clip_start     = $meta_data->start->__toString();
				$clip_end       = $meta_data->end->__toString();
				$clip_filename  = $meta_data->output_filename->__toString();
				$duration       = $clip_end - $clip_start;

				$clip_file_name = $this->get_clip_file_name( $clip_filename, $video );

				$raw_video = $this->ffmpeg->open( $video['file'] );
				$raw_video
					->filters()
					->clip(FFMpeg\Coordinate\TimeCode::fromSeconds( $clip_start ),
						FFMpeg\Coordinate\TimeCode::fromSeconds( $duration ));

				$format = new FFMpeg\Format\Video\X264('libmp3lame', 'libx264');
				###  http://stackoverflow.com/questions/19774975/unknown-encoder-libfaac
				//                $format->on('progress', function ($audio, $format, $percentage) {
				//                    echo "$percentage % transcoded";
				//                });

				$raw_video
					->save( $format, $clip_file_name );

				header('Location: index.html');
				exit;
				#TODO Error handling in library.
				//echo json_encode( $clip_file_name );
			}
		}
	}

	protected function get_clip_file_name( $clip_name, $video ){

		$clip_file_name = $video['name'];
		if ( $clip_name ){
			$clip_file_name = $clip_name .'.'. $video['extension'];
		}
		return $clip_file_name;
	}
}
?>