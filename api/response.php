<?php
namespace OpenSupport\Api;

class Response {
    public static function json(int $statusCode, bool $success, $data = null, ?array $error = null): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => $success,
            'data'    => $data,
            'error'   => $error
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }

    public static function success($data = null, int $statusCode = 200): void {
        self::json($statusCode, true, $data, null);
    }

    public static function error(string $code, string $message, int $statusCode = 400): void {
        self::json($statusCode, false, null, [
            'code'    => $code,
            'message' => $message
        ]);
    }
}