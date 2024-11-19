<?php

use Simon\controller\KiwangoSecurity;
use Simon\controller\PictoryAPI;
use Simon\conf\Config;
use Simon\controller\PictoryJob;
use Simon\controller\PictoryToken;

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = json_decode(file_get_contents('php://input'), true);
    
    $kiwango = new KiwangoSecurity();

    $title = $kiwango->guard($prompt['title']);
    $description = $kiwango->guard($prompt['description']);
    $text = $kiwango->guard($prompt['text']);

    // get access token
    try {
        // Initialize with your credentials
        $clientId = 'your_client_id';
        $clientSecret = 'your_client_secret';
        $pictoryAPI = new PictoryToken($clientId, $clientSecret);
    
        $accessToken = $pictoryAPI->getAccessToken();
        
        if (!$accessToken) {
            $accessToken = $pictoryAPI->getAccessToken();
        }

        // start generate video
        try {
            $authToken = env("PICTORY_AUTH_TOKEN");
            $customerId = env("PRICTORY_CUSTOMER_ID");
            $apiUrl = env("PRICTORY_ENDPOINT");
        
            $pictoryAPI = new PictoryAPI($authToken, $customerId, $apiUrl);
        
            $payload = [
                "audio" => [
                    "aiVoiceOver" => [
                        "speaker" => "Jackson",
                        "speed" => "100",
                        "amplifyLevel" => "1"
                    ],
                    "autoBackgroundMusic" => true,
                    "backGroundMusicVolume" => 0.5
                ],
                "brandLogo" => [
                    "url" => "<YOUR_BRAND_LOGO>",
                    "verticalAlignment" => "bottom",
                    "horizontalAlignment" => "right"
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
                    ]
                ],
                "webhook" => "https://yourdomain.com/conntroller/webhook.php"
            ];
        
            $response = $pictoryAPI->createStoryboard($payload);
        
            $job_id = json_encode(['data' => $response['job_id']]);
    
            if ($job_id) {
                
                try {
                    $jobUrl = "https://api.pictory.ai";
    
                    $pictoryAPI = new PictoryJob($apiUrl, $authToken, $customerId);
                
                    $jobId = $job_id;
                
                    $response = $pictoryjob->getJobDetails($jobId);
                
                    return $job_response;
                } catch (Exception $e) {
                    return "Error: " . $e->getMessage();
                }
                
            }
        } catch (Exception $e) {
            return json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}