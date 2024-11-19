<?php

namespace Simon\Controller;

require_once(__DIR__ . '/../vendor/autoload.php');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class VideoController
{
    public function createVideo()
    {
        $client = new Client();
        $data = [
            'from' => 'Create a video of Africa chickens in three seconds'
        ];

        try {
            $response = $client->request('POST', 'https://apis.elai.io/api/v1/videos/text/text', [
                'json' => $data,
                'headers' => [
                    'Authorization' => 'Bearer RYrx73nHaf5PmSCo974AO7UZFJdveDkJ',
                    'Accept' => 'application/json',
                ],
            ]);

            // Output the response body (you can return or process it as needed)
            echo $response->getBody();
        } catch (RequestException $e) {
            // Handle the error
            echo 'Request failed: ' . $e->getMessage();
        }
    }
}
