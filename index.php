<?php
include "VideoStats.php";

$isvalid="";
$isVideoIdValid="";

if (isset($_POST['submit'])){
    $video_url = $_POST['video_url'];
    if($video_url != ""){
        $isVideoIdValid = preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match);
        if($isVideoIdValid=="1"){
            $video_id = $match[1];
            $video = json_decode(GetVideoInfo($video_id));
            $isvalid = $video->playabilityStatus->status;
            $video_formats = $video->streamingData->formats;
            $thumbnails = $video->videoDetails->thumbnail->thumbnails;
            $video_title = $video->videoDetails->title;
            $channel_id = $video->videoDetails->channelId;
            $channel_uploader = $video->videoDetails->author;
            $thumbnail = end($thumbnails)->url; 
}}}
?>

<!-- Page content !-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
    <link rel="icon" href="img/icon_head.png">
    <link rel="stylesheet" href="style.css">
    <title>YouTube Video Downloader</title>
</head>
<body>

<!--Formulary, URL request_______________________________________-->

    <main>
        <div id="main_header">
            <span class="material-symbols-outlined">
                video_library
            </span>
            <h2>YouTube Video downloader</h2>
        </div>
        <form action=" <?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <input type="text" name="video_url" placeholder="Paste your link here" autocomplete="off">
            <input type="submit" name="submit" value="Download">
        </form>


<!--_____________________Body and info_______________________-->


        <?php
        //If video URL is misspelled or incorrect

        if($isVideoIdValid=="0"){ ?>
            <div class="information_div_box">
                <h3>Please enter the correct url!</h3>
            </div>
        <?php }
        else if($isvalid==""){ ?>

        <!--URL has not been sent yet!-->

        <div class="information_div_box">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" style="transform: ;msFilter:;"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.026 2c-5.509 0-9.974 4.465-9.974 9.974 0 4.406 2.857 8.145 6.821 9.465.499.09.679-.217.679-.481 0-.237-.008-.865-.011-1.696-2.775.602-3.361-1.338-3.361-1.338-.452-1.152-1.107-1.459-1.107-1.459-.905-.619.069-.605.069-.605 1.002.07 1.527 1.028 1.527 1.028.89 1.524 2.336 1.084 2.902.829.091-.645.351-1.085.635-1.334-2.214-.251-4.542-1.107-4.542-4.93 0-1.087.389-1.979 1.024-2.675-.101-.253-.446-1.268.099-2.64 0 0 .837-.269 2.742 1.021a9.582 9.582 0 0 1 2.496-.336 9.554 9.554 0 0 1 2.496.336c1.906-1.291 2.742-1.021 2.742-1.021.545 1.372.203 2.387.099 2.64.64.696 1.024 1.587 1.024 2.675 0 3.833-2.33 4.675-4.552 4.922.355.308.675.916.675 1.846 0 1.334-.012 2.41-.012 2.737 0 .267.178.577.687.479C19.146 20.115 22 16.379 22 11.974 22 6.465 17.535 2 12.026 2z"></path></svg>
            <a href="https://github.com/mayatrajay/Youtube_video_downloader.git"><h3>Github code</h3></a>
        </div>

        <!--If video URL is valid-->

        <?php }else if($isvalid=="OK"){ ?>

        <!--Other video info-->

                        <div class="uploader">
                    <p>Uploaded by:</p>
                    <a href="<?php echo 'https://www.youtube.com/channel/'.$channel_id ?>"
                        target="_blank"><?php echo $channel_uploader ?></a>
                </div>

        <!--Thumbnail image -->

        <div class="video_detail_box">
            <div class="thumbnail_box">
                <img src="<?php echo $thumbnail; ?>" alt="thumbnail">
            </div>

            <?php if(!empty($video_formats)){

        //If the video is copyrighted

    if(@$video_formats[0]->url == ""){ ?>
            <div class="information_div_box">
                <h3>Download is unavailable, this video is copyrighted or private.</h3>
            </div>
        
        <!--Video URL is correct and good to go-->

            <?php }else{ ?>

        <!--Video title-->

            <div class="text_info">
                <a <?php echo 'href="'.$video_url.'" target="blank"'?>><h4><?php echo $video_title; ?></h4></a>
                <hr>
                <table>

                    <tr>
                        <th>Format</th>
                        <th>Download</th>
                    </tr>

                    <?php foreach($video_formats as $format){
                    
        //video formats
            
            if(@$format->url == ""){
                $signature = "https://youtube.com?".$format->signatureCipher;
                parse_str( parse_url( $signature, PHP_URL_QUERY ), $parse_signature );
                $url = $parse_signature['url']."&sig=".$parse_signature['s'];
            }else{
                $url = $format->url;
            }        
            ?>

        <!--Download options-->
                    <?php } ?>

                <tr>
                        <td>Video .MP4
                        </td>
                        <td><a href="VideoDownloader.php?link=<?php echo urlencode($url)?>&title=<?php echo urlencode($video_title)?>&type=<?php echo "mp4";?>"
                                class="download_button">Download</a></td>
                </tr>

                <tr>
                        <td>Video .AVI
                        </td>
                        <td><a href="VideoDownloader.php?link=<?php echo urlencode($url)?>&title=<?php echo urlencode($video_title)?>&type=<?php echo "avi";?>"
                                class="download_button">Download</a></td>
                </tr>

                <tr>
                        <td>Video .WEBM
                        </td>
                        <td><a href="VideoDownloader.php?link=<?php echo urlencode($url)?>&title=<?php echo urlencode($video_title)?>&type=<?php echo "webm";?>"
                                class="download_button">Download</a></td>
                </tr>

                <tr>
                        <td>Audio .MP3
                        </td>
                        <td><a href="VideoDownloader.php?link=<?php echo urlencode($url)?>&title=<?php echo urlencode($video_title)?>&type=<?php echo "mp3";?>"
                                class="download_button">Download</a></td>
                </tr>

                <tr>
                        <td>Audio .WAV
                        </td>
                        <td><a href="VideoDownloader.php?link=<?php echo urlencode($url)?>&title=<?php echo urlencode($video_title)?>&type=<?php echo "wav";?>"
                                class="download_button">Download</a></td>
                </tr>
                </table>
            </div>
        </div>

        <!--If something unusual goes wrong and it's impossible to reach the video-->

        <?php } } }else{ ?>
        <div class="information_div_box">
            <h3>Something went wrong</h3>
            <p>Try again later</p>
        </div>
        <?php } ?>
        </div>
        </div>
        <hr>
    </main>
</body>
</html>
