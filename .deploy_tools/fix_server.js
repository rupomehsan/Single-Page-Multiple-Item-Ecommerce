import SftpClient from "ssh2-sftp-client";
import readline from "readline";

const CONFIG = {
    host: "amadershwapnopuron.com",
    port: 22,
    username: "amadersh",
    remotePath: "/home/amadersh/foodcard.amadershwapnopuron.com",
};

const REQUIRED_DIRS = [
    "bootstrap/cache",
    "storage/logs",
    "storage/framework",
    "storage/framework/cache",
    "storage/framework/sessions",
    "storage/framework/views",
    "storage/app",
    "storage/app/public",
];

function askPassword() {
    if (process.env.CPANEL_PASS) return Promise.resolve(process.env.CPANEL_PASS);
    return new Promise((resolve) => {
        const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
        rl.question("🔐 Password: ", (p) => { rl.close(); resolve(p); });
    });
}

async function fix() {
    const password = await askPassword();
    const sftp = new SftpClient();

    try {
        await sftp.connect({ host: CONFIG.host, port: CONFIG.port, username: CONFIG.username, password });
        console.log("✅ Connected!\n");

        for (const dir of REQUIRED_DIRS) {
            const fullPath = `${CONFIG.remotePath}/${dir}`;
            try {
                await sftp.mkdir(fullPath, true);
                console.log(`✅ Created: ${dir}`);
            } catch (err) {
                console.log(`⚠️  ${dir} — ${err.message}`);
            }
        }

        console.log("\n🎉 Fix complete! Site reload করো।");
    } finally {
        await sftp.end();
    }
}

fix().catch((err) => console.error("❌", err.message));
