<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/api/rate') {
    require 'api/rate.php';
} elseif ($uri === '/api/subscribe') {
    require 'api/subscribe.php';
} 
else {
    http_response_code(404);
    echo json_encode(['error' => 'Not Found']);
}
?>