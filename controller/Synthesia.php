<?php

namespace Simon\controller;

class SynthesiaAPI
{
    private $apiEndpoint;
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiEndpoint = "https://api.synthesia.io/v2";
        $this->apiKey = $apiKey;
    }

    
    public function createVideoFromTemplate($templateId, $templateData)
    {
        $url = "{$this->apiEndpoint}/videos";

        $payload = [
            "templateId" => $templateId,
            "templateData" => $templateData,
        ];

        return $this->sendPostRequest($url, $payload);
    }

    public function getVideoStatus($videoId)
    {
        $url = "{$this->apiEndpoint}/videos/{$videoId}";
        return $this->sendGetRequest($url);
    }

    private function sendPostRequest($url, $data)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer {$this->apiKey}",
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "Error: " . curl_error($ch) . PHP_EOL;
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        return json_decode($response, true);
    }

    private function sendGetRequest($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->apiKey}",
        ]);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo "Error: " . curl_error($ch) . PHP_EOL;
            curl_close($ch);
            return false;
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
