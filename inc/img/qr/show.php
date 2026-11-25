<?php

use klr\SessionMgmt;

header('Cache-Control: private, no-store');
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'GET') {
    header('Allow: GET');
    http_response_code(405);
    exit;
}
$authenticated = SessionMgmt::checkAuth(false) && !SessionMgmt::isRotated();
SessionMgmt::close();
if (!$authenticated) {
    http_response_code(401);
    exit;
}

$value = $_GET['value'] ?? null;
if (!is_string($value) || trim($value) === '' || strlen($value) > 180
    || preg_match('/\p{Cc}/u', $value) !== 0) {
    http_response_code(400);
    exit;
}

require_once __DIR__ . '/../../../lib/phpqrcode/qrlib.php';
$matrix = QRcode::text($value);
if (!$matrix) { throw new RuntimeException('QR encoding failed'); }

// Grayscale PNG with three pixels per module and a four-module white margin.
$scale = 3;
$margin = 4 * $scale;
$size = count($matrix) * $scale + 2 * $margin;
$side = str_repeat("\xff", $margin);
$blank = str_repeat("\x00" . str_repeat("\xff", $size), $margin);
$rows = $blank;
foreach ($matrix as $row) {
    $pixels = strtr($row, array('0' => str_repeat("\xff", $scale), '1' => str_repeat("\x00", $scale)));
    $rows .= str_repeat("\x00" . $side . $pixels . $side, $scale);
}
$data = gzcompress($rows . $blank);
if ($data === false) { throw new RuntimeException('QR image compression failed'); }

$chunk = static function ($type, $bytes) {
    $content = $type . $bytes;
    return pack('N', strlen($bytes)) . $content . pack('N', crc32($content));
};
header('Content-Type: image/png');
header('X-Content-Type-Options: nosniff');
echo "\x89PNG\r\n\x1a\n"
    . $chunk('IHDR', pack('NNCCCCC', $size, $size, 8, 0, 0, 0, 0))
    . $chunk('IDAT', $data)
    . $chunk('IEND', '');
