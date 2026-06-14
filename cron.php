<?php
// Only allow local execution
$allowedIPs = ['127.0.0.1', '::1', 'localhost'];
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowedIPs) && PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Akses tidak dibenarkan.');
}

file_get_contents('http://127.0.0.1' . dirname($_SERVER['SCRIPT_NAME']) . '/web/index.php?r=cron%2Frun-bonus-maintain');
exit;
