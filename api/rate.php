<?php
// Endpoint URL for Monobank API
$url = 'https://api.monobank.ua/bank/currency';

// Fetch data from Monobank API
$data = file_get_contents($url);

// Check if data was fetched successfully
if ($data === false) {
    http_response_code(500);
    die('Failed to fetch data from Monobank API');
}

// Decode JSON response
$response = json_decode($data, true);

// Check if response is valid
if (!$response) {
    http_response_code(500);
    die('Invalid response from Monobank API');
}

// Search for USD to UAH exchange rates
$usdToUahRates = array_filter($response, function($item) {
    return $item['currencyCodeA'] === 840; // 840 is the currency code for USD
});

// Check if rates were found
if (empty($usdToUahRates)) {
    http_response_code(404);
    die('USD to UAH exchange rates not found');
}

// Get the buy and sell rates for USD to UAH
$buyRate = null;
$sellRate = null;

foreach ($usdToUahRates as $rate) {
    if ($rate['currencyCodeB'] === 980) { // 980 is the currency code for UAH
        $buyRate = $rate['rateBuy'];
        $sellRate = $rate['rateSell'];
        break;
    }
}

// Check if both buy and sell rates were found
if ($buyRate === null || $sellRate === null) {
    http_response_code(404);
    die('USD to UAH exchange rates not found');
}

// Prepare response
$responseData = [
    'rateBuy' => $buyRate,
    'rateSell' => $sellRate
];

// Set response headers
header('Content-Type: application/json');

// Return JSON response with buy and sell rates for USD to UAH
echo json_encode($responseData);