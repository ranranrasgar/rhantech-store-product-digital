<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    protected ?Messaging $messaging = null;

    public function __construct()
    {
        // Path to firebase credentials json file
        $credentialsPath = config('services.firebase.credentials') ?: env('FIREBASE_CREDENTIALS');
        
        if ($credentialsPath && file_exists(base_path($credentialsPath))) {
            $factory = (new Factory)->withServiceAccount(base_path($credentialsPath));
            $this->messaging = $factory->createMessaging();
        }
    }

    /**
     * Send Push Notification to User
     *
     * @param User $user
     * @param string $title
     * @param string $body
     * @param array $data Additional data payload
     * @return void
     */
    public function sendNotificationToUser(User $user, $title, $body, $data = [])
    {
        if (!$this->messaging) {
            return;
        }

        $tokens = $user->fcmTokens()->pluck('token')->toArray();

        if (empty($tokens)) {
            return;
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        try {
            $this->messaging->sendMulticast($message, $tokens);
        } catch (\Exception $e) {
            // Log error or ignore
            Log::error('FCM Error: ' . $e->getMessage());
        }
    }
}
