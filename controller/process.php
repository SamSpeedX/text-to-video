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

// Output the result
header("Content-Type: application/json");
$prompt = json_decode(file_get_contents('php://input'), true);
echo json_encode($result);
}
?>

