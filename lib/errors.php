<?php

namespace klr;

// Loaded before configuration so bootstrap failures use the same controlled boundary.
class AppErrors {
    private static $started = false;
    private static $failed = false;
    private static $debug = false;
    private static $bufferLevel = 0;
    private static $phase = 'bootstrap';
    private static $requestId;
    private static $reserve;

    public static function start() {
        if (self::$started) { return; }
        self::$started = true;
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        // Native exception/error dumps can include credentials; the handlers log safe metadata instead.
        ini_set('log_errors', '0');
        error_reporting(E_ALL);
        set_error_handler(array(self::class, 'onError'));
        set_exception_handler(array(self::class, 'onException'));
        register_shutdown_function(array(self::class, 'onShutdown'));
        self::$requestId = bin2hex(random_bytes(12));
        self::$reserve = str_repeat(' ', 65536);
        self::$bufferLevel = ob_get_level();
        ob_start();
    }

    public static function configure($debug) { self::$debug = $debug === true; }
    public static function requestId() { return self::$requestId; }
    public static function phase($phase) {
        if (in_array($phase, array('bootstrap', 'configuration', 'session', 'localization', 'dispatch'), true)) { self::$phase = $phase; }
    }

    public static function onError($severity, $message, $file, $line) {
        if (self::$failed || !(error_reporting() & $severity)) { return true; }
        if (in_array($severity, array(E_DEPRECATED, E_USER_DEPRECATED, E_STRICT), true)) {
            if (self::$debug) { self::log('deprecation', $severity, 200); }
            return true;
        }
        // Never carry the original warning text or arguments into another diagnostic path.
        throw new \ErrorException('Application runtime error', 0, $severity);
    }

    public static function onException($error) {
        $category = $error instanceof \ErrorException ? 'warning'
            : ($error instanceof \ParseError ? 'parse_error' : ($error instanceof \TypeError ? 'type_error' : 'exception'));
        self::fail($category, $error instanceof \ErrorException ? $error->getSeverity() : E_ERROR);
    }

    public static function onShutdown() {
        $error = error_get_last();
        if (!self::$failed && $error !== null && in_array($error['type'], array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR), true)) {
            self::fail('fatal', $error['type']);
        }
    }

    private static function log($category, $severity, $status) {
        try {
            if (class_exists(Diagnostics::class, false)) {
                Diagnostics::event('application_error', array('status' => $status, 'category' => $category,
                    'severity' => $severity, 'phase' => self::$phase));
            } else {
                error_log(json_encode(array('event' => 'application_error', 'request_id' => self::$requestId, 'status' => $status)));
            }
        } catch (\Throwable $ignored) {
            // A logging failure must not replace the controlled response with a second error dump.
        }
    }

    private static function fail($category, $severity) {
        if (self::$failed) { return; }
        self::$failed = true;
        self::$reserve = null;
        self::log($category, $severity, 500);
        while (ob_get_level() > self::$bufferLevel) { ob_end_clean(); }
        if (!headers_sent()) {
            foreach (array('Content-Length', 'Content-Disposition', 'Content-Encoding', 'Location') as $header) { header_remove($header); }
            http_response_code(500);
            header('Cache-Control: no-store');
            header('X-Klr-Request-ID: ' . self::$requestId);
            if (self::$debug) {
                header('X-Klr-Debug-Phase: ' . self::$phase);
                header('X-Klr-Debug-Category: ' . $category);
            }
        }
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = is_string($uri) ? parse_url($uri, PHP_URL_PATH) : '';
        $message = 'Zahteve ni bilo mogoče obdelati. Poskusite znova.';
        if (is_string($path) && ($path === '/ajax' || str_starts_with($path, '/ajax/'))) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(array('CODE' => 500, 'DATA' => $message));
        } else {
            header('Content-Type: text/html; charset=utf-8');
            echo '<!doctype html><html lang="sl"><head><meta charset="utf-8"><title>Napaka</title></head><body><h1>Napaka</h1><p>' . $message . '</p></body></html>';
        }
        exit();
    }
}
