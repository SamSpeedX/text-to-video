<?php
namespace Simon\controller;

class CopilotVideoGenerator
{
  private $apiKey;
  private $copilotEndpoint;

  // Constructor to initialize the API key and endpoint
  public function __construct($apiKey, $copilotEndpoint)
  {
    $this->apiKey = $apiKey;
    $this->copilotEndpoint = $copilotEndpoint;
  }

  // Method to generate video
  public function generateVideo($params)
  {
    // Initialize cURL
    $ch = curl_init();

    // Configure cURL options
    curl_setopt($ch, CURLOPT_URL, $this->copilotEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      "Content-Type: application/json",
      "Authorization: Bearer " . $this->apiKey,
    ]);

    // Execute the request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
      return ["error" => "cURL Error: " . curl_error($ch)];
    }

    // Decode the response
    $result = json_decode($response, true);

    // Close cURL session
    curl_close($ch);

    // Return the response
    return $result;
  }
}
