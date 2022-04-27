<?php

namespace App\Services;

use App\Mail\MailTemplate;
use App\Models\ApiKey;
use App\Models\EmailTemplate;
use App\Models\Notification;
use App\Models\NotificationTemplate;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TerminateExpiredApiKeys
{

    public function terminateKeys()
    {
        $apiKeys = $this->findExpired();
        if (!$apiKeys->count()) {
            return null;
        }

        /** @var EmailTemplate|null $emailTemplate */
        $emailTemplate = EmailTemplate::query()->where('name', 'notify_expired_api_key')->first();

        /** @var NotificationTemplate|null $notificationTemplate */
        $notificationTemplate = NotificationTemplate::query()->where('name', 'notify_expired_api_key')->first();


        foreach ($apiKeys as $apiKey) {
            $apiKey->status = 3;
            $apiKey->save();
            $user = $apiKey->user;
            if ($emailTemplate) {
                $this->sendEmail($apiKey, $user, $emailTemplate);
            }

            if ($notificationTemplate) {
                $this->sendNotification($apiKey, $user, $notificationTemplate);
            }
        }
    }

    public function findExpired()
    {
        return ApiKey::query()->with('user')
            ->where('expires', Carbon::today())
            ->get();
    }


    private function sendEmail(ApiKey $apiKey, User $user, EmailTemplate $template)
    {
        Mail::to($user->email)->send(new MailTemplate($template, $user, null, null, null, $apiKey));
    }


    private function sendNotification(ApiKey $apiKey, User $user, NotificationTemplate $template)
    {
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
