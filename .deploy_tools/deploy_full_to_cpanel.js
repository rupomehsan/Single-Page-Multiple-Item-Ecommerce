import SftpClient from "ssh2-sftp-client";
import fs from "fs";
import path from "path";
import readline from "readline";

const CONFIG = {
    host: "amadershwapnopuron.com",
    port: 22,
    username: "amadersh",
    remotePath: "/home/amadersh/foodcard.amadershwapnopuron.com",
    concurrency: 5,
};

const workspaceRoot = process.cwd();

const IGNORE_LIST = [
    "node_modules", "vendor", ".git", ".deploy_tools",
    ".env", ".env.backup", ".env.production",
    "storage/logs", "storage/framework/cache",
    "storage/framework/sessions", "storage/framework/views",
    "bootstrap/cache", "tests", ".idea", ".vscode",
    "package-lock.json", "DATABASE_DOCUMENTATION.md",
];

function askPassword() {
    if (process.env.CPANEL_PASS) return Promise.resolve(process.env.CPANEL_PASS);
    return new Promise((resolve) => {
        const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
        rl.question("🔐 cPanel Password: ", (p) => { rl.close(); resolve(p); });
    });
}

function shouldIgnore(relPath) {
    const p = relPath.replace(/\\/g, "/").replace(/^\//, "");
    return IGNORE_LIST.some((ig) => p === ig || p.startsWith(ig + "/"));
}

function getAllFiles(dir, baseDir = dir) {
    const results = [];
    for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
        const fullPath = path.join(dir, entry.name);
        const relPath = path.relative(baseDir, fullPath).replace(/\\/g, "/");
        if (shouldIgnore(relPath)) continue;
        if (entry.isDirectory()) results.push(...getAllFiles(fullPath, baseDir));
        else results.push({ localPath: fullPath, relPath: "/" + relPath });
    }
    return results;
}

async function uploadBatch(sftp, files) {
    await Promise.all(files.map(async (file) => {
        const remoteFile = CONFIG.remotePath + file.relPath;
        const remoteDir = path.posix.dirname(remoteFile);
        try {
            await sftp.mkdir(remoteDir, true);
            await sftp.put(file.localPath, remoteFile);
            process.stdout.write(`✅ ${file.relPath}\n`);
        } catch (err) {
            process.stdout.write(`❌ ${file.relPath} — ${err.message}\n`);
        }
    }));
}

async function deployFull() {
    console.log("📂 Files scan করছি...");
    const files = getAllFiles(workspaceRoot);
    console.log(`📋 Total: ${files.length} টা file\n`);

    const password = await askPassword();

    const sftp = new SftpClient();
    try {
        console.log("\n🔌 Connect হচ্ছে...");
        await sftp.connect({ host: CONFIG.host, port: CONFIG.port, username: CONFIG.username, password });
        console.log(`✅ Connected! (${CONFIG.concurrency}টা file একসাথে upload হবে)\n`);

        let done = 0;
        for (let i = 0; i < files.length; i += CONFIG.concurrency) {
            const batch = files.slice(i, i + CONFIG.concurrency);
            await uploadBatch(sftp, batch);
            done += batch.length;
            process.stdout.write(`\r📊 Progress: ${done}/${files.length}\n`);
        }

        console.log(`\n🎉 Full Deploy complete!`);
        console.log(`🌐 https://foodcard.amadershwapnopuron.com`);
    } finally {
        await sftp.end();
    }
}

deployFull().catch((err) => {
    console.error("❌ Error:", err.message);
    process.exit(1);
});
