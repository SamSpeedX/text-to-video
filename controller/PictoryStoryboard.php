<?php

namespace Simon\controller;

use Exception;

class PictoryStoryboard
{
    private $accessToken;
    private $baseUrl = 'https://api.pictory.ai/pictoryapis/v1/video/storyboard';

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Create a storyboard by sending a POST request.
     *
     * @param array $payload The data for the storyboard creation.
     * @return array Response from the API.
     * @throws Exception If an error occurs during the request.
     */
    public function createStoryboard(array $payload)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }

        if ($httpCode !== 200) {
            throw new Exception("API Error: HTTP Code {$httpCode} - Response: " . $response);
        }

        return json_decode($response['video_id'], true);
    }
}
