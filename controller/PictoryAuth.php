<?php

class PictoryAuth
{
    private $clientId;
    private $clientSecret;
    private $tokenUrl;
    private $accessToken;
    private $tokenExpiry;

    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenUrl = 'https://api.pictory.ai/pictoryapis/v1/oauth2/token';
        $this->accessToken = null;
        $this->tokenExpiry = null;
    }

    public function getAccessToken()
    {
        // Check if token exists and is not expired
        if ($this->accessToken && $this->tokenExpiry > time()) {
            return $this->accessToken;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->tokenUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode([
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ]),
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            throw new Exception("Curl Error: " . $error);
        }

        $data = json_decode($response, true);
        if (isset($data['error'])) {
            throw new Exception("API Error: " . $data['error_description']);
        }

        $this->accessToken = $data['access_token'];
        $this->tokenExpiry = time() + $data['expires_in'];

        return $this->accessToken;
    }
}
