<?php

use Simon\controller\PictoryAPI;

try {
    $authToken = "YOUR_AUTH_TOKEN";
    $customerId = "UNIQUE_CUSTOMER_ID";

    $pictoryAPI = new PictoryAPI($authToken, $customerId);

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
