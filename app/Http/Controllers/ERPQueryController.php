<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\OpenAIService;

class ERPQueryController extends Controller
{
    public function processPrompt(Request $request)
    {
        $prompt = $request->input('prompt');

        // Call OpenAI Service (Codex)
        $response = (new OpenAIService())->getCodeFromPrompt($prompt);

        return response()->json([
            'prompt' => $prompt,
            'generated_code' => $response
        ]);
    }
}
