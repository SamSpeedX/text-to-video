<?php

use Simon\controller\PictoryRenderVideo;
use Simon\controller\PictoryRendering;
use Simon\controller\PictoryStoryboard;
use Simon\controller\KiwangoSecurity;
use Simon\Controller\PictoryJob;

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
        $message = "Error: " . $e->getMessage();
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
        $message = "Error: " . $e->getMessage();
    }


    
    try {
        // $webhook = 'https://your.callback.url';
    
        // Initialize the rendering class
        $renderVideo = new PictoryRenderVideo($accessToken);
    
        // Start rendering
        $response = $renderVideo->render($videoId, $webhook);
    
        // Output response
        $renderingJobId = json_encode($response, JSON_PRETTY_PRINT);
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }


    try {
    
        // Initialize the Job class
        $job = new PictoryJob($clientId, $clientSecret);
    
        $videoId = $job->createStoryboard($payload);
        echo "Storyboard created with video ID: $videoId\n";
    
        // Start video rendering
        $renderingJobId = $job->startRendering();
        // echo "Rendering started with Job ID: $renderingJobId\n";
        $message = "Video generation Started...";
    
        // Check job status
        $status = $job->getJobStatus($renderingJobId);
        $message = "Video generation status: " . $status['status'] . "\n";
    
        if ($status['status'] === 'completed') {
            $message = "Video rendering completed!\n";
            $download = $status['downloadUrl'] . "\n";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage() . "\n";
    }
    
   }
   http_response_code(200);
   echo json_encode(['status' => $status['status'], 'message' => $message, 'Download' => $download]);
}
