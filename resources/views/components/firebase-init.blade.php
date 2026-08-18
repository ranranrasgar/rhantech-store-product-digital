<!-- Firebase JS SDK -->
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.22.2/firebase-messaging-compat.js"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('firebaseManager', () => ({
            fcmToken: null,
            initFirebase() {
                const firebaseConfig = {
                    apiKey: "{{ env('FIREBASE_API_KEY') }}",
                    projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
                    messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
                    appId: "{{ env('FIREBASE_APP_ID') }}"
                };

                if (!firebaseConfig.apiKey || typeof firebase === 'undefined') return;

                if (!firebase.apps.length) {
                    firebase.initializeApp(firebaseConfig);
                }

                const messaging = firebase.messaging();

                if ('serviceWorker' in navigator) {
                    const swUrl = `/firebase-messaging-sw.js?apiKey=${firebaseConfig.apiKey}&projectId=${firebaseConfig.projectId}&messagingSenderId=${firebaseConfig.messagingSenderId}&appId=${firebaseConfig.appId}`;
                    navigator.serviceWorker.register(swUrl)
                    .then((registration) => {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                messaging.getToken({ 
                                    vapidKey: "{{ env('FIREBASE_VAPID_KEY') }}",
                                    serviceWorkerRegistration: registration 
                                }).then((currentToken) => {
                                    if (currentToken) {
                                        this.fcmToken = currentToken;
                                        this.sendTokenToServer(currentToken);
                                    }
                                }).catch((err) => {
                                    console.error('An error occurred while retrieving token. ', err);
                                });
                            }
                        });
                    }).catch((err) => {
                        console.error('Service worker registration failed, error:', err);
                    });
                }

                messaging.onMessage((payload) => {
                    console.log('Foreground message received. ', payload);
                    // Dispatch a global event so chat windows can update
                    window.dispatchEvent(new CustomEvent('fcm-message-received', { detail: payload }));

                    // Optional: Show browser notification even in foreground
                    if (Notification.permission === 'granted' && payload.notification) {
                        const notification = new Notification(payload.notification.title, {
                            body: payload.notification.body,
                            icon: '/images/logo.png',
                            data: payload.data
                        });

                        notification.onclick = function() {
                            window.focus();
                            this.close();
                        };
                    }
                });
            },

            sendTokenToServer(token) {
                fetch('{{ route("chat.fcm-token") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        token: token,
                        device_type: 'web'
                    })
                }).catch(() => {});
            }
        }));
    });
</script>
