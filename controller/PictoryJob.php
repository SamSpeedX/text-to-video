<?php
namespace Simon\Controller;

use Exception;

class PictoryJob
{
    private $clientId;
    private $clientSecret;
    private $accessToken;
    private $tokenExpiry;
    private $baseUrl = 'https://api.pictory.ai/pictoryapis/v1';
    private $renderingJobId;
    private $videoId;

    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken = null;
        $this->tokenExpiry = null;
    }

    /**
     * Get a valid access token.
     * @return string Access token.
     * @throws Exception
     */
    private function getAccessToken()
    {
        // If token is still valid, return it
        if ($this->accessToken && $this->tokenExpiry > time()) {
            return $this->accessToken;
        }

        // Otherwise, request a new token
        $url = $this->baseUrl . '/oauth2/token';
        $payload = json_encode([
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret
        ]);

        $response = $this->makeRequest($url, 'POST', $payload);

        if (isset($response['access_token'], $response['expires_in'])) {
            $this->accessToken = $response['access_token'];
            $this->tokenExpiry = time() + $response['expires_in'] - 60;
            return $this->accessToken;
        }

        throw new Exception("Failed to retrieve access token.");
    }

    /**
     * Create a storyboard.
     * @param array $storyboardData
     * @return string Video ID.
     * @throws Exception
     */
    public function createStoryboard($storyboardData)
    {
        $url = $this->baseUrl . '/video/storyboard';
        $payload = json_encode($storyboardData);
        $response = $this->makeRequest($url, 'POST', $payload);

        if (isset($response['video_id'])) {
            $this->videoId = $response['video_id'];
            return $this->videoId;
        }

        throw new Exception("Failed to create storyboard.");
    }

    /**
     * Start the video rendering process.
     * @return string Rendering Job ID.
     * @throws Exception
     */
    public function startRendering()
    {
        if (!$this->videoId) {
            throw new Exception("No video ID found. Please create a storyboard first.");
        }

        $url = $this->baseUrl . '/video/render';
        $payload = json_encode([
            'videoId' => $this->videoId
        ]);
        $response = $this->makeRequest($url, 'POST', $payload);

        if (isset($response['renderingJobId'])) {
            $this->renderingJobId = $response['renderingJobId'];
            return $this->renderingJobId;
        }

        throw new Exception("Failed to start rendering.");
    }

    /**
     * Get the status of the rendering job.
     * @param string $renderingJobId
     * @return array Job status response.
     * @throws Exception
     */
    public function getJobStatus($renderingJobId)
    {
        $url = $this->baseUrl . "/jobs/{$renderingJobId}";
        $response = $this->makeRequest($url, 'GET');

        return $response;
    }

    /**
     * Helper function to make HTTP requests.
     * @param string $url The URL to call.
     * @param string $method The HTTP method (GET, POST, etc.)
     * @param string|null $payload The payload for POST requests.
     * @return array Parsed response.
     * @throws Exception
     */
    private function makeRequest($url, $method, $payload = null)
    {
        $curl = curl_init();

        $headers = [
            'Authorization: ' . $this->getAccessToken(),
            'Content-Type: application/json'
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_TIMEOUT => 30,
        ]);

        if ($method === 'POST') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        } elseif ($method === 'GET') {
            curl_setopt($curl, CURLOPT_HTTPGET, true);
        }

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode >= 400) {
            throw new Exception("Request failed with HTTP code {$httpCode}: {$response}");
        }

        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to decode response: {$response}");
        }

        return $decodedResponse;
    }
}
