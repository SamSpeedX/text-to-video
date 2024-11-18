<?php

use Simon\controller\KiwangoSecurity;
use Simon\controller\PictoryAPI;
use Simon\conf\Config;

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = json_decode(file_get_contents('php://input'), true);
    
    $kiwango = new KiwangoSecurity();

    $title = $kiwango->guard($prompt['title']);
    $text = $kiwango->guard($prompt['text']);
    
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
                "verticalAlignment" => "top",
                "horizontalAlignment" => "right"
            ],
            "videoName" => "Video Name",
            "videoDescription" => "Description Of Video",
            "language" => "en",
            "videoWidth" => "1080",
            "videoHeight" => "1920",
            "scenes" => [
                [
                    "text" => "ENTER TEXT HERE WHICH NEEDS TO BE CONVERTED TO VIDEO",
                    "voiceOver" => true,
                    "splitTextOnNewLine" => true,
                    "splitTextOnPeriod" => true,
                ]
            ],
            "webhook" => "https://yourdomain.com/conntroller/webhook.php"
        ];
    
        $response = $pictoryAPI->createStoryboard($payload);
    
        echo json_encode(['status' => 'success', 'data' => $response]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}