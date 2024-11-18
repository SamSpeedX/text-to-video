<?php

namespace Simon\controller;

class WebhookHandler
{
    public function handleRequest()
    {
        $requestBody = file_get_contents('php://input');
        $data = json_decode($requestBody, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON payload']);
            return;
        }

        $logFile = __DIR__ . '/webhook_log.txt';
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . json_encode($data) . PHP_EOL, FILE_APPEND);

        http_response_code(200);
        echo json_encode(['status' => 'success', 'message' => 'Webhook received successfully']);
    }
}
