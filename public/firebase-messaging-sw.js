importScripts('https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.22.2/firebase-messaging-compat.js');

// This configuration will be overridden by the main page, but providing a default or empty config
// is sometimes necessary for the service worker to initialize correctly.
// You must replace these with your actual Firebase config or inject them during build.
const firebaseConfig = {
    apiKey: new URL(location).searchParams.get('apiKey'),
    projectId: new URL(location).searchParams.get('projectId'),
    messagingSenderId: new URL(location).searchParams.get('messagingSenderId'),
    appId: new URL(location).searchParams.get('appId'),
};

if (firebaseConfig.apiKey) {
    firebase.initializeApp(firebaseConfig);
    const messaging = firebase.messaging();

    messaging.onBackgroundMessage(function(payload) {
        console.log('[firebase-messaging-sw.js] Received background message ', payload);
        const notificationTitle = payload.notification.title;
        const notificationOptions = {
            body: payload.notification.body,
            icon: '/images/logo.png', // Replace with your actual icon
            data: payload.data
        };

        self.registration.showNotification(notificationTitle, notificationOptions);
    });
}

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    // Default URL to open
    let urlToOpen = '/';
    if (event.notification.data && event.notification.data.url) {
        urlToOpen = event.notification.data.url;
    }

    event.waitUntil(
        clients.matchAll({ type: 'window' }).then(windowClients => {
            // Check if there is already a window/tab open with the target URL
            for (var i = 0; i < windowClients.length; i++) {
                var client = windowClients[i];
                if (client.url.includes(urlToOpen) && 'focus' in client) {
                    return client.focus();
                }
            }
            // If not, open a new window
            if (clients.openWindow) {
                return clients.openWindow(urlToOpen);
            }
        })
    );
});
