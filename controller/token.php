<?php

namespace Simon\controller;

class PictoryToken
{
    private $clientId;
    private $clientSecret;
    private $tokenUrl = 'https://api.pictory.ai/pictoryapis/v1/oauth2/token';
    private $accessToken;
    private $tokenExpiry;

    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessToken = null;
        $this->tokenExpiry = null;
    }

    /**
     * Generate a new access token if it doesn't exist or has expired.
     *
     * @return string The access token.
     * @throws Exception If an error occurs during the token generation.
     */
    public function getAccessToken()
    {
        // Check if token exists and is still valid
        if ($this->accessToken && $this->tokenExpiry > time()) {
            return $this->accessToken;
        }

        // Generate a new token
        $curl = curl_init();

        $payload = json_encode([
            "client_id" => $this->clientId,
            "client_secret" => $this->clientSecret
        ]);

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        if (curl_errno($curl)) {
            throw new Exception('cURL Error: ' . curl_error($curl));
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200) {
            throw new Exception("Error: Received HTTP Code {$httpCode} with response: {$response}");
        }

        $data = json_decode($response, true);

        if (!isset($data['access_token'], $data['expires_in'])) {
            throw new Exception("Invalid response: " . $response);
        }

        // Store token and expiry
        $this->accessToken = $data['access_token'];
        $this->tokenExpiry = time() + $data['expires_in'] - 60;

        return $this->accessToken;
    }
}
