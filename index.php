<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($uri === '/api/rate') {
    require 'api/rate.php';
} elseif ($uri === '/api/subscribe') {
    require 'api/subscribe.php';
} 
//else {
//    http_response_code(404);
//    echo json_encode(['error' => 'Not Found']);
//}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe Form</title>
</head>
<body>

<h2>Subscribe to Rate Updates</h2>
<form action="/api/subscribe.php" method="post">
    <label for="email">Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>
    <input type="submit" value="Subscribe">
</form>

</body>
</html>