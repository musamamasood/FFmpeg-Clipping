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

	/**
 	 * Upload video to upload folder.
	 * @param $video_file
	 *
	 * @return $video
	 */
	public function upload_video( $video_file ){

		$video = false;
		$video_files = $this->uploader->upload( $video_file, array(
			'limit' => 1,
			'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
			'required' => false, //Minimum one file is required for upload {Boolean}
			'uploadDir' => 'uploads/', //Upload directory {String}
			'title' => array('{{file_name}}'),
		));

		if( $video_files['isComplete'] ) {
			$video = $video_files['data'];
		}
		if( $video_files['hasErrors'] ){
			$video = $video_files['errors'];
		}
		return $video;
	}

	/**
	 * Generate clips from ffmpeg library.
	 * @param $clips_arr
	 * @param $video_file
	 *
	 * @return array
	 */
	public function video_clipping( $clips_arr, $video_file ){
		$_video     = $this->upload_video( $video_file );
		$video      = $_video['metas'][0];
		$video_name = explode('.', $video['name']);
		$get_chip_name = array();
		if ( !empty($clips_arr) && !isset($_video['errors']) ){
			$counter = 1;
			ob_start();
			foreach ($clips_arr as $clip){

				$clip_start     = $this->hours_to_secods( $clip['start'] );
				$clip_end       = $this->hours_to_secods( $clip['end'] );

				$clip_filename  = $video_name[0] . '_clip_' . $counter++;
				$duration       = $clip_end - $clip_start;

				$clip_file_name = "uploads/" . $this->get_clip_file_name( $clip_filename, $video );

				$raw_video = $this->ffmpeg->open( $video['file'] );
				$raw_video
					->filters()
					->clip(FFMpeg\Coordinate\TimeCode::fromSeconds( $clip_start ),
						FFMpeg\Coordinate\TimeCode::fromSeconds( $duration ));

				$format = new FFMpeg\Format\Video\X264('libmp3lame', 'libx264');

				$raw_video
					->save( $format, $clip_file_name );

				$get_chip_name[] = $clip_file_name;
			}
			return $get_chip_name;
		}
	}

	/**
	 * Create Clipname from video name and clip name.
	 * @param $clip_name
	 * @param $video
	 *
	 * @return string
	 */
	protected function get_clip_file_name( $clip_name, $video ){

		$clip_file_name = $video['name'];
		if ( $clip_name ){
			$clip_file_name = $clip_name .'.'. $video['extension'];
		}
		return $clip_file_name;
	}

	/**
	 * Convert time into seconds
	 * @param $hour
	 *
	 * @return int
	 */
	protected function hours_to_secods($hour) { // $hour must be a string type: "HH:mm:ss"

        $parse = array();
	    if (!preg_match ('#^(?<mins>[\d]{2}):(?<secs>[\d]{2}).(?<millisecs>[\d]{2})$#',$hour,$parse)) {
	         // Throw error, exception, etc
	         throw new RuntimeException ("Hour Format not valid");
	    }
		return (int) $parse['mins'] * 60 + (int) $parse['secs'] + + (int) $parse['millisecs'];
	}
}
?>