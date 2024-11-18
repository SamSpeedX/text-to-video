<?php
// Adjust the path to autoload.php
require __DIR__ . '/../vendor/autoload.php';

// Adjust the path to the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
