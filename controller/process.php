<?php
session_start();
require_once __DIR__."../vendor/autoload.php";

use Simon\controller\CopilotVideoGenerator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  


$endpoint = env('ENDPOINT');
$apiKey = env('API_KEY');


$videoGenerator = new CopilotVideoGenerator($apiKey, $endpoint);


$params = [
  "template_id" => "1",
  "text" => "{$prompt} generate video as professional",
  //'audio_url' => 'URL_TO_AUDIO_FILE',
  // Other parameters Copilot requires...
];

// Generate video
$result = $videoGenerator->generateVideo($params);


public function synthesia() {
  try {
    $apiKey = env('SYNTHESIA_API');
    $synthesia = new SynthesiaAPI($apiKey);

    // Create a video
    $templateId = env('SYNTHESIA_TEMPLATE');
    $templateData = [
        "name" => "SAM OCHU",
        "company" => "SAM TECHNOLOGY",
    ];

    $createResponse = $synthesia->createVideoFromTemplate($templateId, $templateData);

    if ($createResponse) {
        echo "Video creation started. Video ID: " . $createResponse['id'] . PHP_EOL;

        // Get video status
        $videoId = $createResponse['id'];
        $statusResponse = $synthesia->getVideoStatus($videoId);

        if ($statusResponse) {
            echo "Video Status: " . $statusResponse['status'] . PHP_EOL;
        } else {
            echo "Failed to retrieve video status." . PHP_EOL;
        }
    } else {
        echo "Video creation failed." . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
}}
  
}
// Output the result
header("Content-Type: application/json");
$prompt = json_decode(file_get_contents('php://input'), true);
echo json_encode($result);
}
?>

