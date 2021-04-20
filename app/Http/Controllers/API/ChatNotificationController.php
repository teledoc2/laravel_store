<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\FirebaseMessagingTrait;
use Illuminate\Http\Request;


class ChatNotificationController extends Controller
{
    use FirebaseMessagingTrait;
    //
    public function send(Request $request)
    {

        //
        try {
            $orderData = [
                'is_chat' => "1",
                'path' => $request->path,
                'user' => json_encode($request->user),
                'peer' => json_encode($request->peer),
            ];
            $this->sendFirebaseNotification($request->topic, $request->title, $request->body, $orderData);

            //
            return response()->json([
                "message" => "Notification sent successfully"
            ], 200);
        } catch (\Exception $ex) {
            return response()->json([
                "message" => $ex->getMessage() ?? "Notification failed"
            ], 400);
        }
    }
}
