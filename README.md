# FFmpeg Clipping
Script help to do clipping of videos for sepecific duration from the uploaded video. 
Clone this script to generate the clips based on start and end time. 

##Please make these configuration before running script. 

- Increase post_max_size to 64M in php.ini 
    **post_max_size = 256M**
- Pleas make sure [FFmpeg](https://www.ffmpeg.org/) libraries [installed](https://github.com/adaptlearning/adapt_authoring/wiki/Installing-FFmpeg) by run these command in terminal **which ffmpeg** and **which ffprobe**
  if you missing **ffprobe** then install ffprobe through **sudo apt-get install libav-tools**
- Add duration as array in form_upload.php at line # 10
```php
    array(
        array( 'start' => '00:00.00', 'end' => '00:30.00' ),
        array( 'start' => '01:05.00', 'end' => '01:12.00' ),
        array( 'start' => '02:00.00', 'end' => '02:59.00' ),
    );