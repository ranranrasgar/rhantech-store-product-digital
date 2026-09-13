<!-- Firebase JS SDK -->
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js"></script>

<script>
(function() {
    // 1. Web Audio Chime Synthesizer
    window.playNotificationChime = function() {
        try {
            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            const ctx = new AudioContext();
            if (ctx.state === 'suspended') {
                ctx.resume();
            }
            const now = ctx.currentTime;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            osc.type = 'sine';
            osc.frequency.setValueAtTime(587.33, now); // D5
            osc.frequency.exponentialRampToValueAtTime(880, now + 0.15); // A5

            gain.gain.setValueAtTime(0.2, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.6);

            osc.connect(gain);
            gain.connect(ctx.destination);

            osc.start(now);
            osc.stop(now + 0.6);
        } catch (e) {
            console.warn('[FCM Audio]', e);
        }
    };

    // Helper untuk trigger update navbar bell
    window.triggerBellUpdate = function() {
        window.dispatchEvent(new CustomEvent('fcm-message-received'));
    };

    const rawVapid = "{{ config('services.firebase.vapid_key') ?: env('FIREBASE_VAPID_KEY') }}";
    const cleanVapidKey = (rawVapid && rawVapid.length > 80 && !rawVapid.includes('>')) ? rawVapid : null;

    const firebaseConfig = {
        apiKey: "{{ config('services.firebase.api_key') ?: env('FIREBASE_API_KEY') }}" || "AIzaSyAdbvpPM0glcMC8Q608NNa2-6ANLG3d0y9",
        projectId: "{{ config('services.firebase.project_id') ?: env('FIREBASE_PROJECT_ID') }}" || "rhantech-produk-digital",
        messagingSenderId: "{{ config('services.firebase.messaging_sender_id') ?: env('FIREBASE_MESSAGING_SENDER_ID') }}" || "931909062658",
        appId: "{{ config('services.firebase.app_id') ?: env('FIREBASE_APP_ID') }}" || "1:931909062658:web:6f106d114e6810d4fb971b",
        vapidKey: cleanVapidKey
    };

    let messaging = null;

    function initFirebaseMessaging() {
        if (typeof firebase === 'undefined') return;

        if (!firebase.apps.length) {
            try {
                firebase.initializeApp(firebaseConfig);
            } catch (e) {
                console.warn('[FCM] Firebase init notice:', e);
            }
        }

        try {
            messaging = firebase.messaging();
        } catch (e) {
            console.warn('[FCM] Messaging unsupported or restricted:', e);
            return;
        }

        // Listener pesan foreground
        messaging.onMessage((payload) => {
            console.log('[FCM Foreground Message]:', payload);
            window.playNotificationChime();

            // Dispatch global event agar semua bell di navbar otomatis refresh
            window.dispatchEvent(new CustomEvent('fcm-message-received', { detail: payload }));

            const title = payload.notification?.title || payload.data?.title || 'Pemberitahuan Baru';
            const body = payload.notification?.body || payload.data?.body || '';
            const clickUrl = payload.data?.url || payload.data?.click_action || '/';

            // Tampilkan native OS desktop notification jika izin sudah ada
            if (Notification.permission === 'granted') {
                try {
                    const notif = new Notification(title, {
                        body: body,
                        icon: '/images/logo.png',
                        data: { url: clickUrl, ...(payload.data || {}) }
                    });

                    notif.onclick = function() {
                        window.focus();
                        if (clickUrl && clickUrl !== '/') {
                            window.location.href = clickUrl;
                        }
                        this.close();
                    };
                } catch(e) {}
            }
        });
    }

    // Mengirim token ke backend Laravel
    async function sendTokenToServer(token) {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
            const response = await fetch('/fcm-token', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    token: token,
                    device_type: 'web'
                })
            });
            const data = await response.json();
            console.log('[FCM] Device token registered:', data);
        } catch (err) {
            console.warn('[FCM] Token sync notice:', err);
        }
    }

    // Registrasi Token FCM dari Service Worker
    async function registerAndSaveToken() {
        if (!('serviceWorker' in navigator)) return;

        let currentToken = null;

        // 1. Coba lewat Firebase Messaging jika ready
        if (messaging) {
            try {
                const swUrl = `/firebase-messaging-sw.js?apiKey=${firebaseConfig.apiKey}&projectId=${firebaseConfig.projectId}&messagingSenderId=${firebaseConfig.messagingSenderId}&appId=${firebaseConfig.appId}`;
                const registration = await navigator.serviceWorker.register(swUrl);
                await registration.update();

                const tokenOptions = { serviceWorkerRegistration: registration };
                if (firebaseConfig.vapidKey) {
                    tokenOptions.vapidKey = firebaseConfig.vapidKey;
                }

                currentToken = await messaging.getToken(tokenOptions);
            } catch (err) {
                console.warn('[FCM SDK token fallback]:', err);
            }
        }

        // 2. Fallback device token jika SDK belum mendapatkan token
        if (!currentToken) {
            let localToken = localStorage.getItem('rhantech_fcm_token');
            if (!localToken) {
                localToken = 'rhantech-device-' + Math.random().toString(36).substring(2, 12) + '-' + Date.now();
            }
            currentToken = localToken;
        }

        if (currentToken) {
            localStorage.setItem('rhantech_fcm_token', currentToken);
            await sendTokenToServer(currentToken);
            return currentToken;
        }
    }

    // Auto-check saat halaman selesai dimuat
    function checkNotificationStatus() {
        initFirebaseMessaging();

        if (!('Notification' in window)) return;

        if (Notification.permission === 'granted') {
            registerAndSaveToken();
        } else if (Notification.permission === 'default') {
            // Minta izin secara halus saat pengguna berinteraksi pertama kali
            const requestOnce = () => {
                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        registerAndSaveToken();
                    }
                }).catch(() => {});
                document.removeEventListener('click', requestOnce);
            };
            document.addEventListener('click', requestOnce, { once: true });
        }
    }

    @if(session('fcm_notification'))
    // Mainkan suara lonceng & picu update bell saat halaman dibuka dengan notifikasi baru
    setTimeout(() => {
        window.playNotificationChime();
        window.triggerBellUpdate();
    }, 300);
    @endif

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', checkNotificationStatus);
    } else {
        checkNotificationStatus();
    }
})();
</script>
