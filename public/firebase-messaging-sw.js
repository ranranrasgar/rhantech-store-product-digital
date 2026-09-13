// Service Worker untuk Background Push Notification Firebase Cloud Messaging (FCM)
// Proyek: RHanTech Store Produk Digital

importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

// Ambil config dari query URL atau gunakan fallback default proyek
const urlParams = new URL(location).searchParams;
const firebaseConfig = {
    apiKey: urlParams.get('apiKey') || "AIzaSyAdbvpPM0glcMC8Q608NNa2-6ANLG3d0y9",
    projectId: urlParams.get('projectId') || "rhantech-produk-digital",
    messagingSenderId: urlParams.get('messagingSenderId') || "931909062658",
    appId: urlParams.get('appId') || "1:931909062658:web:6f106d114e6810d4fb971b"
};

try {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage(function(payload) {
        console.log('[SW FCM] onBackgroundMessage received:', payload);
        // Handler push di bawah yang akan memanggil showNotification()
    });
} catch (err) {
    console.warn('[SW FCM] Init error:', err);
}

// Handler Push Event Tunggal yang Pasti Memanggil showNotification()
// Mencegah Chrome memunculkan pesan default: "This site has been updated in the background"
self.addEventListener('push', function(event) {
    console.log('[SW FCM] Push event received:', event);

    let title = '🔔 Pemberitahuan Baru';
    let body = 'Ada pembaruan status pada akun Anda.';
    let clickUrl = '/';
    let notifTag = 'rhantech-notification';
    let notifData = {};

    if (event.data) {
        try {
            const payload = event.data.json();
            console.log('[SW FCM] Parsed JSON payload:', payload);

            title = payload.notification?.title || payload.data?.title || title;
            body = payload.notification?.body || payload.data?.body || body;
            clickUrl = payload.data?.url || payload.data?.click_action || payload.fcmOptions?.link || '/';
            notifData = payload.data || {};

            if (notifData.type && notifData.invoice) {
                notifTag = 'rhantech-' + notifData.type + '-' + notifData.invoice;
            } else if (notifData.type) {
                notifTag = 'rhantech-' + notifData.type;
            }
        } catch (e) {
            body = event.data.text() || body;
        }
    }

    const options = {
        body: body,
        icon: '/images/logo.png',
        badge: '/images/logo.png',
        tag: notifTag,
        renotify: true,
        requireInteraction: true,
        vibrate: [200, 100, 200, 100, 200],
        data: {
            url: clickUrl,
            ...notifData
        },
        actions: [
            { action: 'open_url', title: 'Buka Detail' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Handler saat notifikasi di klik
self.addEventListener('notificationclick', function(event) {
    console.log('[SW FCM] Notification click:', event);
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url) ? event.notification.data.url : '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(windowClients) {
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url.includes(targetUrl) && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
