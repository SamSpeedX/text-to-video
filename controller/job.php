<?php

namespace Simon\controller;

class PictoryJob
{
    private $jobUrl;
    private $authToken;
    private $customerId;

    public function __construct($jobUrl, $authToken, $customerId)
    {
        $this->jobUrl = $jobUrl;
        $this->authToken = $authToken;
        $this->customerId = $customerId;
    }

    /**
     * Fetch Job Details
     *
     * @param string $jobId The ID of the job to retrieve.
     * @return array The API response as an associative array.
     * @throws Exception If an error occurs during the API call.
     */
    public function getJobDetails($jobId)
    {
        $url = $this->jobUrl . "/pictoryapis/v1/jobs/" . $jobId;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: ' . $this->authToken,
                'X-Pictory-User-Id: ' . $this->customerId,
                'accept: application/json'
            ]
        ]);

        // Execute the request
        $response = curl_exec($curl);

        // Handle errors
        if (curl_errno($curl)) {
            throw new Exception('cURL Error: ' . curl_error($curl));
        }

        // Get HTTP response code
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        // Validate response
        if ($httpCode !== 200) {
            throw new Exception("API Error: Received HTTP Code {$httpCode} with response: {$response}");
        }

        // Return response as an associative array
        return json_decode($response, true);
    }
}
