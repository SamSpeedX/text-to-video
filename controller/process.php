<?php

header("Content-Type: application/json");

require_once __DIR__."../vendor/autoload.php";

use Simon\conntroller\PictoryAPI;
use Simon\controller\CopilotVideoGenerator;
use Simon\controller\SynthesiaAPI;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prompt = json_decode(file_get_contents('php://input'), true);
    $tittle = $prompt['tittle'];
    $text = $prompt['text'];

    $endpoint = env('COPILOT_ENDPOINT');
    $apiKey = env('COPILOT_API');

    $videoGenerator = new CopilotVideoGenerator($apiKey, $endpoint);
    
    $params = [
      "template_id" => env('COPILOT_TEMPLATE'),
      "text" => "{$text} generate video as professional",
      //'audio_url' => 'URL_TO_AUDIO_FILE',
      // Other parameters Copilot requires...
    ];
    
    // Generate video
    $copilot_result = $videoGenerator->generateVideo($params);
    
    // $synthesia_key = env("SYNTHESIA_KEY");
    // function synthesia($text, $tittle, $synthesia_key)
    //  {
    //   try {
    //     $apiKey = env('SYNTHESIA_API');
    //     $synthesia = new SynthesiaAPI($synthesia_key);
    
    //     // Create a video
    //     $templateId = env('SYNTHESIA_TEMPLATE');
    //     $templateData = [
    //         "name" => "SAM OCHU",
    //         "company" => "SAM TECHNOLOGY",
    //     ];
    
    //     $createResponse = $synthesia->createVideoFromTemplate($templateId, $templateData);
    
    //     if ($createResponse) {
    //         return "Video creation started. Video ID: " . $createResponse['id'] . PHP_EOL;
    
    //         $videoId = $createResponse['id'];
    //         $statusResponse = $synthesia->getVideoStatus($videoId);
    
    //         if ($statusResponse) {
    //             return "Video Status: " . $statusResponse['status'] . PHP_EOL;
    //         } else {
    //             return "Failed to retrieve video status." . PHP_EOL;
    //         }
    //     } else {
    //         return "Video creation failed." . PHP_EOL;
    //     }
    // } catch (Exception $e) {
    //     return "Exception: " . $e->getMessage() . PHP_EOL;
    // }
      
    // }
    
    
    try {
        $Pictory_authToken = env("PICTORY_AUTH_TOKEN");
        $Pictory_customerId = env("PRICTORY_CUSTOMER_ID");
        $prictory_endpoint = env("PICTORY_ENDPOINT");
    
        $pictoryAPI = new PictoryAPI($Pictory_authToken, $Pictory_customerId, $prictory_endpoint);
    
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
            "webhook" => "<WEBHOOK_CALLBACK_URL>"
        ];
    
        $response = $pictoryAPI->createStoryboard($payload);
    
        print_r($response);
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
    
    
    $synthesia_result = synthesia($text, $tittle, $synthesia_key);
    $prictory_response = prictory($text, $tittle, $prictory_key);
    
    $response = [
      "copilot" => $copilot_result,
      "prictory" => $synthesia_result,
      "synthesia" => $synthesia_result
    ];
    
    echo json_encode($response);
}
?>

