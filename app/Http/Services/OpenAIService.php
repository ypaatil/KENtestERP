<?php

namespace App\Http\Services;

class OpenAIService
{
    protected $apiKey;
    protected $endpoint = 'https://api.openai.com/v1/chat/completions';

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function getCodeFromPrompt($prompt)
    {
        $postData = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a Laravel expert. Generate only Laravel Eloquent query or raw SQL as per the prompt.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.2,
        ];
    
        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiKey,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
    
        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
    
        if ($error) {
            return 'cURL Error: ' . $error;
        }
    
        $data = json_decode($response, true);
    
        // 🔍 Log full raw response to Laravel log
        \Log::info('OpenAI Raw Response:', $data);
    
        return $data['choices'][0]['message']['content'] ?? 'No code generated.';
    }

}
