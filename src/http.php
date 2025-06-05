<?php
function http_get_json(string $url): array {
    $context = stream_context_create([
        'http' => [
            'timeout' => 10,
            'ignore_errors' => true,
        ]
    ]);
    $resp = @file_get_contents($url, false, $context);
    if ($resp === false) {
        return ['error' => 'request failed'];
    }
    $data = json_decode($resp, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'invalid json'];
    }
    return $data;
}
?>
