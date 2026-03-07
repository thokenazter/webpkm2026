<?php
/**
 * GitHub Webhook Handler untuk Auto Deployment
 * File ini akan menerima webhook dari GitHub dan menjalankan deployment otomatis
 */

// Konfigurasi
$secret = 'your-webhook-secret-here'; // Ganti dengan secret yang aman
$logFile = 'deployment.log';
$deployScript = '../deploy.sh';

// Function untuk logging
function writeLog($message) {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}

// Function untuk response
function respond($code, $message) {
    http_response_code($code);
    echo json_encode(['status' => $code, 'message' => $message]);
    exit;
}

// Verifikasi method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, 'Method not allowed');
}

// Ambil payload
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// Verifikasi signature jika secret diset
if (!empty($secret)) {
    $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    if (!hash_equals($expectedSignature, $signature)) {
        writeLog('Invalid signature from IP: ' . $_SERVER['REMOTE_ADDR']);
        respond(403, 'Invalid signature');
    }
}

// Parse payload
$data = json_decode($payload, true);
if (!$data) {
    writeLog('Invalid JSON payload');
    respond(400, 'Invalid JSON payload');
}

// Cek apakah ini push ke main branch
if (!isset($data['ref']) || $data['ref'] !== 'refs/heads/main') {
    writeLog('Ignoring push to branch: ' . ($data['ref'] ?? 'unknown'));
    respond(200, 'Ignoring non-main branch');
}

// Log deployment start
$commitHash = $data['after'] ?? 'unknown';
$pusher = $data['pusher']['name'] ?? 'unknown';
writeLog("Deployment started - Commit: $commitHash, Pusher: $pusher");

// Jalankan deployment script
if (!file_exists($deployScript)) {
    writeLog("Deploy script not found: $deployScript");
    respond(500, 'Deploy script not found');
}

// Make script executable
chmod($deployScript, 0755);

// Execute deployment
$output = [];
$returnCode = 0;
exec("cd .. && ./deploy.sh 2>&1", $output, $returnCode);

// Log hasil
$outputString = implode("\n", $output);
writeLog("Deployment output:\n$outputString");

if ($returnCode === 0) {
    writeLog("Deployment completed successfully");
    respond(200, 'Deployment completed successfully');
} else {
    writeLog("Deployment failed with return code: $returnCode");
    respond(500, 'Deployment failed');
}
?>