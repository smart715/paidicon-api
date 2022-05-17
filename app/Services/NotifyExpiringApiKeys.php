<?php

namespace App\Services;

use App\Mail\MailTemplate;
use App\Models\ApiKey;
use App\Models\EmailTemplate;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NotifyExpiringApiKeys
{

    public function notify()
    {
        $apiKeys = $this->findExpiring();

        if (!$apiKeys->count()) {
            return null;
        }

        /** @var EmailTemplate|null $emailTemplate */
        $emailTemplate = EmailTemplate::query()->where('name', 'notify_expiring_api_key')->first();

        /** @var NotificationTemplate|null $notificationTemplate */
        $notificationTemplate = NotificationTemplate::query()->where('name', 'notify_expiring_api_key')->first();

        foreach ($apiKeys as $apiKey) {
            $user = $apiKey->user;
            if ($emailTemplate) {
                $this->sendEmail($apiKey, $user, $emailTemplate);
            }

            if ($notificationTemplate) {
                $this->sendNotification($apiKey, $user, $notificationTemplate);
            }
        }
    }

    private function findExpiring()
    {
        return ApiKey::query()->with('user')
            ->whereDate('expires', Carbon::today()->addDays(10))
            ->orWhereDate('expires', Carbon::today()->addDays(5))
            ->orWhereDate('expires', Carbon::today()->addDays(3))
            ->orWhereDate('expires', Carbon::today()->addDay())->get();
    }

    private function sendEmail(ApiKey $apiKey, User $user, EmailTemplate $template)
    {
        $keyDaysLeft = Carbon::today()->diffInDays($apiKey->expires);

        Log::info('Sending ['.$keyDaysLeft.'days left] notification expiration notification to '. $user->uuid);
        Mail::to($user->email)->send(new MailTemplate($template, $user, null, null, null, $apiKey));
    }

    private function sendNotification(ApiKey $apiKey, User $user, NotificationTemplate $template)
    {
        $keyDaysLeft = Carbon::today()->diffInDays($apiKey->expires);

        Log::info('Sending ['.$keyDaysLeft.'days left] notification expiration notification to '. $user->uuid);
        $messageParser = new MessageParser($user, null, null, null, $apiKey);
        Notification::create([
                                 'uuid' => (string)Str::orderedUuid(),
                                 'title' => $messageParser->parseContent($template->title),
                                 'content' => $messageParser->parseContent($template->content),
                                 'user_id' => $user->id,
                                 'updated_by_user_id' => null
                             ]);
    }
}
