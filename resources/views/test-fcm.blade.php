<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Uji Coba Push Notification (FCM) - RHanTech</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 680px;
            background: #1e293b;
            border-radius: 24px;
            border: 1px solid #334155;
            padding: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #334155;
        }
        .logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        h1 { font-size: 20px; font-weight: 800; color: #ffffff; }
        p.subtitle { font-size: 13px; color: #94a3b8; margin-top: 2px; }
        .status-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 24px;
        }
        .status-card {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 14px;
            padding: 14px;
        }
        .status-label { font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .status-value { font-size: 16px; font-weight: 700; margin-top: 4px; display: flex; align-items: center; gap: 8px; }
        .badge-green { color: #4ade80; }
        .badge-yellow { color: #facc15; }
        .badge-red { color: #f87171; }
        .btn-group { display: flex; flex-direction: column; gap: 10px; margin-bottom: 24px; }
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            text-align: center;
        }
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.4);
        }
        .btn-primary:hover { opacity: 0.95; transform: translateY(-1px); }
        .btn-secondary {
            background: #334155;
            color: #f8fafc;
        }
        .btn-secondary:hover { background: #475569; }
        .btn-action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .log-box {
            background: #090d16;
            border: 1px solid #1e293b;
            border-radius: 12px;
            padding: 12px 16px;
            font-family: ui-monospace, SFMono-Regular, monospace;
            font-size: 12px;
            color: #38bdf8;
            max-height: 140px;
            overflow-y: auto;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo-icon">🔔</div>
        <div>
            <h1>Uji Coba Push Notifikasi (FCM)</h1>
            <p class="subtitle">RHanTech Store Digital Platform Multi-Actor Notification</p>
        </div>
    </div>

    <div class="status-grid">
        <div class="status-card">
            <div class="status-label">Izin Notifikasi Browser</div>
            <div id="perm-display" class="status-value badge-yellow">Mengecek...</div>
        </div>
        <div class="status-card">
            <div class="status-label">Total Token di Database</div>
            <div id="token-count-display" class="status-value badge-green">{{ $tokenCount ?? 0 }} Token</div>
        </div>
    </div>

    <div class="btn-group">
        <button id="btn-request-perm" class="btn btn-primary" onclick="requestNotification()">
            👉 1. Klik di Sini untuk Mengizinkan & Mengaktifkan Notifikasi
        </button>

        <button class="btn btn-secondary" onclick="sendTestNotification('general')">
            🚀 2. Kirim Tes Notifikasi ke Desktop Saya
        </button>

        <div class="btn-action-grid">
            <button class="btn btn-secondary" onclick="sendTestNotification('order')">
                🛒 Tes Notif Pesanan Baru
            </button>
            <button class="btn btn-secondary" onclick="sendTestNotification('paid')">
                💰 Tes Notif Bayar Lunas
            </button>
        </div>
    </div>

    <div style="font-size: 11px; color: #64748b; margin-bottom: 6px; font-weight: 600; text-transform: uppercase;">Log Aktivitas:</div>
    <div id="log-box" class="log-box">
        [Info] Halaman pengujian siap. Silakan klik tombol di atas.
    </div>
</div>

@include('components.firebase-init')

<script>
    function log(msg) {
        const box = document.getElementById('log-box');
        const time = new Date().toLocaleTimeString('id-ID');
        box.innerHTML += `\n[${time}] ${msg}`;
        box.scrollTop = box.scrollHeight;
    }

    function updatePermissionBadge() {
        const el = document.getElementById('perm-display');
        const perm = Notification.permission;
        if (perm === 'granted') {
            el.className = 'status-value badge-green';
            el.innerHTML = '✓ Diizinkan (Aktif)';
            document.getElementById('btn-request-perm').style.display = 'none';
        } else if (perm === 'denied') {
            el.className = 'status-value badge-red';
            el.innerHTML = '✕ Diblokir Browser';
        } else {
            el.className = 'status-value badge-yellow';
            el.innerHTML = '⏳ Belum Diizinkan';
        }
    }

    async function requestNotification() {
        log('Meminta izin notifikasi dari browser...');
        if (window.activateRhantechNotification) {
            await window.activateRhantechNotification();
        } else {
            const p = await Notification.requestPermission();
        }
        updatePermissionBadge();
        log('Status izin saat ini: ' + Notification.permission);
        refreshDatabaseTokenCount();
    }

    async function refreshDatabaseTokenCount() {
        try {
            const res = await fetch('/test-fcm-push?json=1');
            const data = await res.json();
            document.getElementById('token-count-display').innerText = data.fcm_tokens_registered + ' Token';
        } catch(e) {}
    }

    async function sendTestNotification(type) {
        log(`Mengirim perintah push notifikasi (${type}) ke server...`);
        try {
            const res = await fetch(`/test-fcm-push?action=${type}&json=1`);
            const data = await res.json();
            log(`Respons server: ${data.message} (${data.fcm_tokens_registered} target token)`);
            refreshDatabaseTokenCount();
        } catch (e) {
            log('Gagal mengirim perintah: ' + e.message);
        }
    }

    // Periksa status saat halaman dibuka
    document.addEventListener('DOMContentLoaded', () => {
        updatePermissionBadge();
        setInterval(updatePermissionBadge, 1500);
    });
</script>

</body>
</html>
