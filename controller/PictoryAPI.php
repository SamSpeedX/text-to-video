<?php

namespace Simon\conntroller;

class PictoryAPI
{
    private $apiEndpoint;
    private $apiKey;

    public function __construct(string $apiEndpoint, string $apiKey)
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->apiKey = $apiKey;
    }


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
