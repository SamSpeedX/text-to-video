<?php

class ElaiAPI {
    private $apiKey;
    private $apiUrl;

    // Constructor to initialize the API key and URL
    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
        $this->apiUrl = 'https://api.elai.io/v1/video/generate';
    }

    // Method to send a request to the Elai.io API and return the response
    public function generateVideo($text, $language = 'en', $voice = 'en_us_male') {
        // Data to be sent to the API
        $data = [
            'text' => $text,
            'language' => $language,
            'voice' => $voice
        ];

        // Send request and return response
        $response = $this->sendRequest($data);
        return $response;
    }

    // Private method to handle cURL request
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

// Usage Example

try {
    // Initialize with your API Key
    $apiKey = 'YOUR_API_KEY'; // Replace with your actual Elai.io API key
    $elaiAPI = new ElaiAPI($apiKey);

    // Generate a video with the desired text
    $text = 'This is a test video generated from text using Elai.io API.';
    $response = $elaiAPI->generateVideo($text);

    // Check the response and display the result
    if (isset($response['video_url'])) {
        echo "Video URL: " . $response['video_url'];
    } else {
        echo "Failed to generate video. Response: " . json_encode($response);
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
