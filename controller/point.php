<?php
header("Content-Type: application/json");

use Simon\Controller\VideoController;

$videoController = new VideoController();
$videoController->createVideo();
