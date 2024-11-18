<?php

header("Content-Type: application/json");

require_once __DIR__."../vendor/autoload.php";

use Simon\controller\CopilotVideoGenerator;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $prompt = json_decode(file_get_contents('php://input'), true);
    $tittle = $prompt['tittle'];
    $text = $prompt['text'];

    $endpoint = env('COPILOT_ENDPOINT');
    $apiKey = env('COPILOT_API');

    $videoGenerator = new CopilotVideoGenerator($apiKey, $endpoint);
    
    $params = [
      "template_id" => env('COPILOT_TEMPLATE'),
      "text" => "{$this->text} generate video as professional",
      //'audio_url' => 'URL_TO_AUDIO_FILE',
      // Other parameters Copilot requires...
    ];
    
    // Generate video
    $copilot_result = $videoGenerator->generateVideo($params);
    
    
    function synthesia($text, $tittle)
     {
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
            return "Video creation started. Video ID: " . $createResponse['id'] . PHP_EOL;
    
            // Get video status
            $videoId = $createResponse['id'];
            $statusResponse = $synthesia->getVideoStatus($videoId);
    
            if ($statusResponse) {
                return "Video Status: " . $statusResponse['status'] . PHP_EOL;
            } else {
                return "Failed to retrieve video status." . PHP_EOL;
            }
        } else {
            return "Video creation failed." . PHP_EOL;
        }
    } catch (Exception $e) {
        return "Exception: " . $e->getMessage() . PHP_EOL;
    }}
      
    }
    
    function prictory($text,$tittle) 
    {
      try {
        // Initialize the API
        $api = new PictoryAPI("https://api.pictory.ai/v1/text-to-video", env('PRICTORY_API');
    
        // Generate a video
        $response = $api->generateVideo(
            $tittle,
            $text
        );
    
        // Handle the response
        if (isset($response['videoUrl'])) {
            return "Video generated successfully! Download URL: " . $response['videoUrl'] . PHP_EOL;
        } elseif (isset($response['error'])) {
            return "Error: " . $response['error'] . PHP_EOL;
        } else {
            return "Error: " . $response['message'] . PHP_EOL;
        }
    } catch (Exception $e) {
        return "Exception: " . $e->getMessage() . PHP_EOL;
      }
      
    }
    
    $synthesia_result = synthesia();
    $prictory_response = prictory();
    
    $response = [
      "copilot" => $copilot_result,
      "prictory" => $synthesia_result,
      "synthesia" => $synthesia_result
    ];
    
    echo json_encode($response);
}
?>

