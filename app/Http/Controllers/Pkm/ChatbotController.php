<?php

namespace App\Http\Controllers\Pkm;

use App\Http\Controllers\Controller;
use App\Services\GroqService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    /**
     * Handle chatbot message.
     */
    public function chat(Request $request, GroqService $groqService): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'nullable|array|max:10',
            'history.*.role' => 'required_with:history|string|in:user,assistant',
            'history.*.content' => 'required_with:history|string|max:1000',
        ]);

        $result = $groqService->chat(
            $request->input('message'),
            $request->input('history', [])
        );

        return response()->json($result);
    }
}
