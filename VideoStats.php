<?php


function GetVideoInfo($video_id){
    $api_secret_key = getenv('CONVERT_API_SECRET_KEY"');
    $curl_request = curl_init();
    
    curl_setopt($curl_request, CURLOPT_URL, "https://www.youtube.com/youtubei/v1/player?key=$api_secret_key");
    curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_request, CURLOPT_POST, 1);
    curl_setopt($curl_request, CURLOPT_POSTFIELDS, '{  "context": {    "client": {      "hl": "en",      "clientName": "WEB",      "clientVersion": "2.20210721.00.00",      "clientFormFactor": "UNKNOWN_FORM_FACTOR",   "clientScreen": "WATCH",      "mainAppWebInfo": {        "graftUrl": "/watch?v='.$video_id.'",           }    },    "user": {      "lockedSafetyMode": false    },    "request": {      "useSsl": true,      "internalExperimentFlags": [],      "consistencyTokenJars": []    }  },  "videoId": "'.$video_id.'",  "playbackContext": {    "contentPlaybackContext": {        "vis": 0,      "splay": false,      "autoCaptionsDefaultOn": false,      "autonavState": "STATE_NONE",      "html5Preference": "HTML5_PREF_WANTS",      "lactMilliseconds": "-1"    }  },  "racyCheckOk": false,  "contentCheckOk": false}');
    curl_setopt($curl_request, CURLOPT_ENCODING, 'gzip, deflate');
    
    $headers = array();
    $headers[] = 'Content-Type: application/json';
    curl_setopt($curl_request, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($curl_request);
    if (curl_errno($curl_request)) {
        echo 'Error:' . curl_error($curl_request);
    }
    curl_close($curl_request);
    return $response;
    
    }
?>