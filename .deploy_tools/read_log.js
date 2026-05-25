import SftpClient from "ssh2-sftp-client";

const CONFIG = {
    host: "amadershwapnopuron.com",
    port: 22,
    username: "amadersh",
    remotePath: "/home/amadersh/foodcard.amadershwapnopuron.com",
};

async function uploadDebug() {
    const password = process.env.CPANEL_PASS;
    if (!password) { console.error("❌ Set CPANEL_PASS env variable"); process.exit(1); }

    const sftp = new SftpClient();
    try {
        await sftp.connect({ host: CONFIG.host, port: CONFIG.port, username: CONFIG.username, password });

        const phpDebug = `<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo '<pre>';

// Test DB connection
$host = 'localhost';
$db   = 'amadersh_foodcart';
$user = 'amadersh_foodcart';
$pass = '-1P%2hTHR((zvUXf';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    echo "DB (localhost): CONNECTED OK\\n";
} catch(Exception $e) {
    echo "DB (localhost) ERROR: " . $e->getMessage() . "\\n";
}

try {
    $pdo2 = new PDO("mysql:host=127.0.0.1;dbname=$db", $user, $pass);
    echo "DB (127.0.0.1): CONNECTED OK\\n";
} catch(Exception $e) {
    echo "DB (127.0.0.1) ERROR: " . $e->getMessage() . "\\n";
}

// Test storage writable
$storagePath = __DIR__ . '/../storage/logs';
echo "storage/logs writable: " . (is_writable($storagePath) ? "YES" : "NO") . "\\n";

$cachePath = __DIR__ . '/../bootstrap/cache';
echo "bootstrap/cache writable: " . (is_writable($cachePath) ? "YES" : "NO") . "\\n";

// Try full Laravel boot
try {
    define('LARAVEL_START', microtime(true));
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\\Contracts\\Http\\Kernel::class);
    $response = $kernel->handle(
        $request = Illuminate\\Http\\Request::capture()
    );
    echo "Laravel boot: OK\\n";
    echo "HTTP Status: " . $response->getStatusCode() . "\\n";
} catch(Throwable $e) {
    echo "Laravel ERROR: " . $e->getMessage() . "\\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\\n";
}

echo '</pre>';`;

        await sftp.put(Buffer.from(phpDebug), `${CONFIG.remotePath}/public/_debug.php`);
        console.log("✅ Debug file uploaded!");
        console.log("👉 https://foodcard.amadershwapnopuron.com/_debug.php");
    } finally {
        await sftp.end();
    }
}

uploadDebug().catch(console.error);
