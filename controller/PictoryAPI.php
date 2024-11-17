<?php

namespace sam\conntroller;

class PictoryAPI
{
    private $apiEndpoint;
    private $apiKey;

    /**
     * Constructor to initialize API endpoint and key.
     * 
     * @param string $apiEndpoint
     * @param string $apiKey
     */
    public function __construct(string $apiEndpoint, string $apiKey)
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->apiKey = $apiKey;
    }

    /**
     * Generates a video from text using the Pictory.ai API.
     * 
     * @param string $title
     * @param string $content
     * @param string $style
     * @return array Response data from the API
     */
    public function generateVideo(string $title, string $content, string $style = "default"): array
    {
        // Prepare the payload
        $data = [
            "script" => [
                "title" => $title,
                "content" => $content,
            ],
            "style" => $style,
        ];

        // Initialize cURL
        $ch = curl_init($this->apiEndpoint);

        // Set cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->apiKey}",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute request
        $response = curl_exec($ch);

        // Handle cURL errors
        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return ["error" => $error];
        }

        // Close cURL
        curl_close($ch);

        // Decode and return the response
        return json_decode($response, true);
    }
}

// Example Usage
try {
    // Initialize the API
    $api = new PictoryAPI("https://api.pictory.ai/v1/text-to-video", "your-api-key-here");

    // Generate a video
    $response = $api->generateVideo(
        "Your Video Title",
        "This is the text for the video. It will be used to generate the content."
    );

    // Handle the response
    if (isset($response['videoUrl'])) {
        echo "Video generated successfully! Download URL: " . $response['videoUrl'] . PHP_EOL;
    } elseif (isset($response['error'])) {
        echo "Error: " . $response['error'] . PHP_EOL;
    } else {
        echo "Error: " . $response['message'] . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . PHP_EOL;
}
