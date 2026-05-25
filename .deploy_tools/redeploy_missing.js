import SftpClient from "ssh2-sftp-client";
import fs from "fs";
import path from "path";

const CONFIG = {
    host: "amadershwapnopuron.com",
    port: 22,
    username: "amadersh",
    remotePath: "/home/amadersh/foodcard.amadershwapnopuron.com",
};

const workspaceRoot = process.cwd();

// Only these directories need re-upload
const TARGET_DIRS = ["app", "Modules", "config", "routes", "resources", "database", "public"];

function getAllFiles(dir, baseDir = workspaceRoot) {
    const results = [];
    if (!fs.existsSync(dir)) return results;
    for (const entry of fs.readdirSync(dir, { withFileTypes: true })) {
        const fullPath = path.join(dir, entry.name);
        const relPath = path.relative(baseDir, fullPath).replace(/\\/g, "/");
        if (entry.isDirectory()) results.push(...getAllFiles(fullPath, baseDir));
        else results.push({ localPath: fullPath, relPath: "/" + relPath });
    }
    return results;
}

async function redeploy() {
    const password = process.env.CPANEL_PASS;
    if (!password) { console.error("❌ Set CPANEL_PASS"); process.exit(1); }

    const files = TARGET_DIRS.flatMap(d => getAllFiles(path.join(workspaceRoot, d)));
    console.log(`📋 ${files.length} টা file upload হবে...\n`);

    const sftp = new SftpClient();
    try {
        await sftp.connect({ host: CONFIG.host, port: CONFIG.port, username: CONFIG.username, password });
        console.log("✅ Connected!\n");

        let done = 0;
        for (const file of files) {
            const remoteFile = CONFIG.remotePath + file.relPath;
            try {
                await sftp.mkdir(path.posix.dirname(remoteFile), true);
                await sftp.put(file.localPath, remoteFile);
                done++;
                if (done % 50 === 0) console.log(`📊 ${done}/${files.length} done...`);
            } catch (err) {
                console.log(`❌ ${file.relPath} — ${err.message}`);
            }
        }

        console.log(`\n✅ ${done}/${files.length} files uploaded!`);
        console.log("🎉 Done! Site reload করো।");
    } finally {
        await sftp.end();
    }
}

redeploy().catch(console.error);
