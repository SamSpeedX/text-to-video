<?php

use Simon\controller\PictoryRenderVideo;
use Simon\controller\PictoryRendering;
use Simon\controller\PictoryStoryboard;
use Simon\controller\KiwangoSecurity;

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = json_decode(file_get_contents('php://input'), true);
    
    $kiwango = new KiwangoSecurity();

    $title = $kiwango->guard($prompt['title']);
    $description = $kiwango->guard($prompt['description']);
    $text = $kiwango->guard($prompt['text']);

    // get access token
   try {
    $clientId = env('CLIENT_ID');
    $clientSecret = env('CLIENT_SECRET');

    $pictoryAuth = new PictoryAuth($clientId, $clientSecret);
    $access = $pictoryAuth->getAccessToken();
    $accessToken = json_encode($access, true);
    
   } catch (Exception $e) {
    throw new Exception("Error Processing Request", $e->getCode(), $e);
   }

   if ($accessToken) {    
    try {
        // Access token from authentication
        $accessToken = 'your_access_token';
    
        // Instantiate the storyboard class
        $storyboard = new PictoryStoryboard($accessToken);
    
        // Define the payload
        $payload = [
            "audio" => [
                "aiVoiceOver" => [
                    "speaker" => "Jackson",
                    "speed" => "100",
                    "amplifyLevel" => "1",
                ],
                "autoBackgroundMusic" => true,
                "backGroundMusicVolume" => 0.5,
            ],
            "brandLogo" => [
                "url" => "<YOUR_BRAND_LOGO>",
                "verticalAlignment" => "top",
                "horizontalAlignment" => "right",
            ],
            "videoName" => $title,
            "videoDescription" => $description,
            "language" => "en",
            "videoWidth" => "1080",
            "videoHeight" => "1920",
            "scenes" => [
                [
                    "text" => $text,
                    "voiceOver" => true,
                    "splitTextOnNewLine" => true,
                    "splitTextOnPeriod" => true,
                ],
            ],
            "webhook" => "https://vediototext.com/controller/webhook.php",
        ];
    
        // Create the storyboard
        $response = $storyboard->createStoryboard($payload);
        $videoId = json_encode($response['video_id'], JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
    
   }

   if ($videoId) {    
    try {
    
        // Initialize the rendering class
        $rendering = new PictoryRendering($accessToken);
    
        // Start rendering
        $response = $rendering->startRendering($videoId, $webhook);
    
        // Output the API response
        $renderId = json_encode($response, JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }


    
    try {
        // Replace with your actual access token and video ID
        // $accessToken = 'your_access_token';
        // $videoId = 'your_video_id';
    
        // Optional webhook URL
        // $webhook = 'https://your.callback.url';
    
        // Initialize the rendering class
        $renderVideo = new PictoryRenderVideo($accessToken);
    
        // Start rendering
        $response = $renderVideo->render($videoId, $webhook);
    
        // Output response
        $renderingJobId = json_encode($response, JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }


    $status = $pictoryApi->getJobStatus($renderingJobId);
    if ($status['status'] === 'completed') {
        $downloadUrl = $status['downloadUrl']; // Get the download link
        echo "Video rendered! Download here: $downloadUrl";
    } elseif ($status['status'] === 'failed') {
        echo "Rendering failed: " . $status['errorMessage'];
    }
    
   }
}