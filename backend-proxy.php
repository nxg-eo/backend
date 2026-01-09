<?php
// backend-proxy.php - Generic proxy for Node.js backend with logging

// Enable error logging to a file
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/proxy-error.log');
error_reporting(E_ALL);

function log_message($message) {
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents(__DIR__ . '/proxy-debug.log', "[$timestamp] " . $message . "\n", FILE_APPEND);
}

log_message("--- Proxy script started ---");

// Configuration - point to your public Node backend
define('BACKEND_BASE_URL', 'https://backend-production-c14ce.up.railway.app');

// Determine the backend path from the request URI
$request_uri = $_SERVER['REQUEST_URI'];
log_message("Request URI: " . $request_uri);

$request_path = parse_url($request_uri, PHP_URL_PATH);

// The base path for our backend routes on the frontend server
$base_path_on_frontend = '/ai-for-business/backend';

$backend_path = '/'; // Default path
if (strpos($request_path, $base_path_on_frontend) === 0) {
    $backend_path = substr($request_path, strlen($base_path_on_frontend));
}

if (empty($backend_path)) {
    $backend_path = '/';
}
log_message("Calculated backend path: " . $backend_path);

// Get the original query string
$query_string = parse_url($request_uri, PHP_URL_QUERY);

// Build the full URL to the Node backend
$api_url = BACKEND_BASE_URL . $backend_path . ($query_string ? '?' . $query_string : '');
log_message("Proxying to API URL: " . $api_url);

// Use cURL to fetch Node response
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

$response_with_headers = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);

log_message("Backend response HTTP code: " . $httpcode);
if ($curlErr) {
    log_message("cURL Error: " . $curlErr);
}

if ($response_with_headers === false) {
    log_message("cURL execution failed.");
    http_response_code(502); // Bad Gateway
    echo json_encode(['success' => false, 'error' => 'Backend request failed', 'details' => $curlErr]);
    exit;
}

// Separate headers and body
$headers = substr($response_with_headers, 0, $header_size);
$body = substr($response_with_headers, $header_size);

// If the backend returned a redirect, forward it
if (in_array($httpcode, [301, 302, 303, 307, 308])) {
    $location = '';
    $header_lines = explode("\r\n", $headers);
    foreach ($header_lines as $header) {
        if (stripos($header, 'Location:') === 0) {
            $location = trim(substr($header, strlen('Location:')));
            break;
        }
    }
    if ($location) {
        log_message("Redirecting to: " . $location);
        header('Location: ' . $location, true, $httpcode);
        exit;
    } else {
        log_message("Redirect status code received, but no Location header found.");
    }
}

// For other responses, forward the body and content-type
log_message("Forwarding response body. Length: " . strlen($body));
$header_lines = explode("\r\n", $headers);
foreach ($header_lines as $header) {
    if (stripos($header, 'Content-Type:') === 0) {
        header($header);
        break;
    }
}

http_response_code($httpcode); // Forward the status code from the backend
echo $body;
log_message("--- Proxy script finished ---");
exit;

?>
