<?php

namespace Simon\controller;

class PictoryRendering
{
    private $accessToken;
    private $baseUrl = 'https://api.pictory.ai/pictoryapis/v1/video/rendering';

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Initiates video rendering.
     *
     * @param string $videoId The ID of the video to render.
     * @param string|null $webhook Optional webhook URL for status updates.
     * @return array The API response.
     * @throws Exception If the request fails.
     */
    public function startRendering($videoId, $webhook = null)
    {
        $curl = curl_init();

        $payload = [
            "videoId" => $videoId,
        ];

        if ($webhook) {
            $payload["webhook"] = $webhook;
        }

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->baseUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
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

        return json_decode($response, true);
    }
}
