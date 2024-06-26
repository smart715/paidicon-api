<?php


namespace App\Http\Controllers\API;

use App\Http\Requests\CreateCustomNotificationMultipleRequest;
use App\Http\Requests\CreateCustomNotificationRequest;
use App\Services\UserFilteringService;
use App\Models\Notification;
use App\Services\MessageParser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomNotificationController
{
    /** @var UserFilteringService */
    private $userFilteringService;

    public function __construct(UserFilteringService $userFilteringService)
    {
        $this->userFilteringService = $userFilteringService;
    }

    public function send(CreateCustomNotificationRequest $request): JsonResponse
    {
        $request['uuid'] = (string) Str::orderedUuid();
        $notification = new Notification($request->all());
        $authUser = auth()->user();
        Log::info('User #'. $authUser->uuid.' '. $authUser->full_name. ' sent custom notification to '. $request->user_id);

        $notification->save();

        return response()->json(['message' => 'Success!']);
    }

    public function sendMultiple(CreateCustomNotificationMultipleRequest $request): JsonResponse
    {
        $users = $this->userFilteringService->filter($request->get('filters'));
        if (!$users->count()) {
            return response()->json(['message' => 'Users not found, try to change the filters'], 404);
        }

        $authUser = auth()->user();

        foreach ($users as $user) {
            $messageParser = new MessageParser($user);
            Log::info('User #'. $authUser->uuid.' '. $authUser->full_name.
                      ' sent custom notification to '. $user->user_id);

            Notification::create([
                'uuid' => (string) Str::orderedUuid(),
                'title' => $messageParser->parseContent($request->get('title')),
                'content' => $messageParser->parseContent($request->get('content')),
                'user_id' => $user->id,
                'updated_by_user_id' => null
            ]);
        }

        return response()->json(['message' => 'Success!']);
    }

}
