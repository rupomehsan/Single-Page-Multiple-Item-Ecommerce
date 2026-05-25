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
const trackingFile = path.join(workspaceRoot, ".deploy_tools/.modify_tracking/modified.json");

function askPassword() {
    if (process.env.CPANEL_PASS) return Promise.resolve(process.env.CPANEL_PASS);
    return new Promise((resolve) => {
        const rl = readline.createInterface({ input: process.stdin, output: process.stdout });
        rl.question("🔐 cPanel Password: ", (p) => { rl.close(); resolve(p); });
    });
}

async function deploy() {
    if (!fs.existsSync(trackingFile)) {
        console.error("❌ Tracking file নেই। আগে চালাও: npm run track");
        process.exit(1);
    }

    const modifiedFiles = JSON.parse(fs.readFileSync(trackingFile, "utf8"));

    if (modifiedFiles.length === 0) {
        console.log("✅ কোনো modified file নেই।");
        process.exit(0);
    }

    console.log(`\n📋 Deploy হবে (${modifiedFiles.length}টা file):`);
    modifiedFiles.forEach((f) => console.log("  " + f));
    console.log("");

    const password = await askPassword();
    const sftp = new SftpClient();

    try {
        console.log("\n🔌 Connect হচ্ছে...");
        await sftp.connect({ host: CONFIG.host, port: CONFIG.port, username: CONFIG.username, password });
        console.log("✅ Connected!\n");

        for (let i = 0; i < modifiedFiles.length; i += CONFIG.concurrency) {
            const batch = modifiedFiles.slice(i, i + CONFIG.concurrency);
            await Promise.all(batch.map(async (relPath) => {
                const localFile = path.join(workspaceRoot, relPath);
                const remoteFile = CONFIG.remotePath + relPath.replace(/\\/g, "/");
                if (!fs.existsSync(localFile)) {
                    console.log(`⚠️  Skip: ${relPath}`);
                    return;
                }
                try {
                    await sftp.mkdir(path.posix.dirname(remoteFile), true);
                    await sftp.put(localFile, remoteFile);
                    console.log(`✅ ${relPath}`);
                } catch (err) {
                    console.log(`❌ ${relPath} — ${err.message}`);
                }
            }));
        }

        fs.writeFileSync(trackingFile, "[]");
        console.log("\n🎉 Deploy complete!");
        console.log("🌐 https://foodcard.amadershwapnopuron.com");
    } finally {
        await sftp.end();
    }
}

deploy().catch((err) => {
    console.error("❌ Error:", err.message);
    process.exit(1);
});
