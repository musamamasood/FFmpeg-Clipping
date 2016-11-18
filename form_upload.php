<?php
    require('vendor/autoload.php');
    require('classes/class.uploader.php');
    require('classes/class.ffmpeg_clipping.php');


    /**
     * Clip Timing Array
     */
    $clips_arr = array(
        array(
            'start' => '00:00.00',
            'end' => '00:30.00',
        ),
        array(
            'start' => '01:05.00',
            'end' => '01:12.00',
        ),
        array(
            'start' => '02:00.00',
            'end' => '02:59.00',
        ),
    );
    /**
     * Inilize objects from classes
     */
    $uploader   = new Uploader();
    $ffmpeg     = FFMpeg\FFMpeg::create();
    $ffmpeg_clipping = new FFMpeg_Clipping( $uploader, $ffmpeg );

    /**
     * Generate clips
     */
	$get_chip_name = $ffmpeg_clipping->video_clipping( $clips_arr, $_FILES['files'] );
	echo json_encode($get_chip_name[0]);
?>
