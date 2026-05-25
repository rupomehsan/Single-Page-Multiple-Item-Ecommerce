import SftpClient from "ssh2-sftp-client";
import readline from "readline";

const CONFIG = {
    host: "amadershwapnopuron.com",
    port: 22,
    username: "amadersh",
    remotePath: "/home/amadersh/foodcard.amadershwapnopuron.com",
};

function askPassword() {
    if (process.env.CPANEL_PASS) return Promise.resolve(process.env.CPANEL_PASS);
    return new Promise((resolve) => {
        const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
        rl.question("🔐 Password: ", (p) => { rl.close(); resolve(p); });
    });
}

async function check() {
    const password = await askPassword();
    const sftp = new SftpClient();

    try {
        await sftp.connect({ host: CONFIG.host, port: CONFIG.port, username: CONFIG.username, password });
        console.log("✅ Connected!\n");

        const checks = [
            { path: `${CONFIG.remotePath}/vendor`, label: "vendor/" },
            { path: `${CONFIG.remotePath}/vendor/autoload.php`, label: "vendor/autoload.php" },
            { path: `${CONFIG.remotePath}/public/index.php`, label: "public/index.php" },
            { path: `${CONFIG.remotePath}/public/.htaccess`, label: "public/.htaccess" },
            { path: `${CONFIG.remotePath}/.env`, label: ".env" },
            { path: `${CONFIG.remotePath}/bootstrap/cache`, label: "bootstrap/cache/" },
            { path: `${CONFIG.remotePath}/storage`, label: "storage/" },
            { path: `${CONFIG.remotePath}/storage/logs`, label: "storage/logs/" },
        ];

        for (const check of checks) {
            try {
                const exists = await sftp.exists(check.path);
                console.log(`${exists ? "✅" : "❌"} ${check.label}`);
            } catch {
                console.log(`❌ ${check.label}`);
            }
        }

    } finally {
        await sftp.end();
    }
}

check().catch((err) => console.error("❌", err.message));
