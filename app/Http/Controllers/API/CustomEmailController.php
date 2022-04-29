<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SendCustomEmailRequest;
use App\Http\Requests\SendMultipleCustomEmailsRequest;
use App\Mail\CustomEmail;
use App\Mail\MailTemplate;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Services\UserFilteringService;
use Illuminate\Support\Facades\Mail;

class CustomEmailController extends Controller
{
    /** @var UserFilteringService */
    private $userFilteringService;

    public function __construct(UserFilteringService $userFilteringService)
    {
        $this->userFilteringService = $userFilteringService;
    }

    public function send(SendCustomEmailRequest $request): \Illuminate\Http\JsonResponse
    {
        $user = User::query()->find($request->get('user_id'));
        $authUser = auth()->user();
        Mail::to($user->email)->send(new CustomEmail($request));

        Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' sent custom email to '. $user->email);

        return response()->json(['message' => 'Success!']);
    }

    public function sendMultiple(SendMultipleCustomEmailsRequest $request)
    {
        $users = $this->userFilteringService->filter($request->get('filters'));
        if (!$users->count()) {
            return response()->json(['message' => 'Users not found, try to change the filters'], 404);
        }

        $emailTemplate = new EmailTemplate();
        $emailTemplate->fill(
            [
                'uuid' => bin2hex(random_bytes(8)),
                'name' => bin2hex(random_bytes(8)),
                'subject' => $request->get('subject'),
                'header' => $request->get('header'),
                'signature' => $request->get('signature'),
                'body' => $request->get('body'),
                'footer' => $request->get('footer'),
            ]
        );
        $authUser = auth()->user();

        foreach ($users as $user) {
            Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' sent custom email to '. $user->email);

            Mail::to($user->email)->send(new MailTemplate($emailTemplate, $user));
        }

        return response()->json(['message' => 'Success!']);
    }
}
