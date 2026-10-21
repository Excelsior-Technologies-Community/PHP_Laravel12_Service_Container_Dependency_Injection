<?php

namespace App\Http\Controllers;

use App\Services\Notifications\MultiChannelBroadcastManager;
use Illuminate\Http\Request;

class ContainerBroadcastController extends Controller
{
    /**
     * Dependency Injected MultiChannelBroadcastManager (resolved via container tag)
     */
    public function __construct(protected MultiChannelBroadcastManager $broadcastManager)
    {
    }

    /**
     * Display Tagged Broadcast Studio.
     */
    public function index()
    {
        $registeredChannels = $this->broadcastManager->getRegisteredChannels();

        return view('container.broadcast', compact('registeredChannels'));
    }

    /**
     * Dispatch notification through all tagged channels.
     */
    public function send(Request $request)
    {
        $recipient = trim($request->input('recipient', 'alex.developer@example.com'));
        $message = trim($request->input('message', 'Deployment v2.4.0 completed successfully in production.'));
        $selectedChannels = $request->input('channels', []);

        if (empty($message)) {
            return response()->json(['success' => false, 'message' => 'Notification message cannot be empty.'], 422);
        }

        $result = $this->broadcastManager->broadcast($recipient, $message, $selectedChannels);

        return response()->json([
            'success' => true,
            'message' => "Broadcast successfully routed through {$result['total_channels']} tagged channels in {$result['total_time_ms']}ms.",
            'data' => $result,
        ]);
    }
}
