<?php

namespace Simon\controller;

class PictoryAPI
{
    private $apiUrl;
    private $authToken;
    private $customerId;

    public function __construct($authToken, $customerId)
    {
        $this->authToken = $authToken;
        $this->customerId = $customerId;
    }

    public function createStoryboard($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'accept: application/json',
                'content-type: application/json',
                'Authorization: ' . $this->authToken,
                'X-Pictory-User-Id: ' . $this->customerId
            ],
            CURLOPT_POSTFIELDS => json_encode($data),
        ]);

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            throw new Exception('cURL Error: ' . curl_error($curl));
        }

        curl_close($curl);

        return json_decode($response, true);
    }
}
