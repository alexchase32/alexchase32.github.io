<?php
// proxy.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if (!isset($_GET['url'])) {
    echo json_encode(["error" => "No URL provided"]);
    exit;
}

$url = $_GET['url']; // must be your Google Apps Script exec URL

// Forward POST or GET data
$options = [
    'http' => [
        'method' => $_SERVER['REQUEST_METHOD'],
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => file_get_contents("php://input")
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);

if ($response === FALSE) {
    echo json_encode(["error" => "Request failed"]);
} else {
    echo $response;
}
?>
