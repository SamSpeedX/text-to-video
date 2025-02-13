<?php

class VeedAPI {
    private $apiKey;
    private $apiUrl;

    // Constructor kuanzisha API Key na URL
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
        $this->apiUrl = 'https://api.veed.io/v1/video/generate'; // URL ya API ya Veed.io
    }

    // Method ya kutuma ombi kwa API ili kuunda video
    public function createVideo($text, $title) {
        // Data ya kutuma kwa API
        $data = [
            'title' => $title,
            'content' => $text
        ];

        // Kutuma request na kurudisha majibu
        return $this->sendRequest($data);
    }

    // Private method ya kutuma cURL request
    private function sendRequest($data) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        // Execute the request and capture the response
        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception('Error occurred: ' . curl_error($ch));
        }

        curl_close($ch);
        return json_decode($response, true); // Decode the response to an associative array
    }
}

// Example Usage

try {
    // Initialize with your Veed.io API Key
    $apiKey = 'YOUR_API_KEY'; // Badilisha na API key yako
    $veedAPI = new VeedAPI($apiKey);

    // Create a video with the desired text
    $text = "This is a test video generated from text using Veed.io API.";
    $title = "Sample Video Title";
    $response = $veedAPI->createVideo($text, $title);

    // Display the response (e.g., video URL)
    if (isset($response['video_url'])) {
        echo "Video URL: " . $response['video_url'];
    } else {
        echo "Failed to create video. Response: " . json_encode($response);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
