<?php
    require('vendor/autoload.php');
    require('classes/class.uploader.php');
    require('classes/class.ffmpeg_clipping.php');

	//
	##TODO
	# Set path for clips

    $uploader = new Uploader();
    $logger = new Monolog\Logger('ffmpeg');
    $logger->pushHandler(new Monolog\Handler\StreamHandler('php://stdout'));

    $ffmpeg = FFMpeg\FFMpeg::create(array(
        'ffmpeg.binaries'  => 'ffmpeg-lib/bin/ffmpeg.exe',
        'ffprobe.binaries' => 'ffmpeg-lib/bin/ffprobe.exe',
        'timeout'          => 3600, // The timeout for the underlying process
        'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
    ), $logger );
    //var_dump($logger); exit();
    $ffmpeg_clipping = new FFMpeg_Clipping( $uploader, $ffmpeg );
    $ffmpeg_clipping->video_clipping( $_FILES['metafiles'], $_FILES['files'] );
?>
