<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class TaskPlannerService
{
    /**
     * Break down a task description into atomic steps using OpenAI.
     *
     * @param string $task
     * @return string[]
     */
    public function breakdown(string $task): array
    {
        $apiKey = config('services.openai.api_key');
        $response = Http::withToken($apiKey)
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant that breaks down tasks into atomic steps.'],
                    ['role' => 'user', 'content' => "Break down the following task into atomic steps:\n\n{$task}"],
                ],
                'max_tokens' => 500,
            ]);
        $content = $response->json('choices.0.message.content', '');
        return $this->parseSteps($content);
    }

    /**
     * Parse the AI response into step array.
     *
     * @param string $text
     * @return string[]
     */
    protected function parseSteps(string $text): array
    {
        $lines = preg_split('/\r?\n/', $text) ?: [];
        $steps = [];
        foreach ($lines as $line) {
            $trim = trim($line);
            if (preg_match('/^(?:\d+[\.)]|-)\s*(.+)$/', $trim, $m)) {
                $steps[] = trim($m[1]);
            }
        }
        if (empty($steps) && trim($text) !== '') {
            $steps[] = trim($text);
        }
        return $steps;
    }
}