<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicProvider implements AIProviderInterface
{
    /**
     * @var string API key
     */
    protected string $apiKey;
    
    /**
     * @var string Base URL for API requests
     */
    protected string $baseUrl;
    
    /**
     * @var string Default model to use
     */
    protected string $defaultModel;
    
    /**
     * @var array Default parameters
     */
    protected array $defaultParams;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $config = config('services.ai_providers.anthropic', []);
        $this->apiKey = $config['api_key'] ?? '';
        $this->baseUrl = $config['base_url'] ?? 'https://api.anthropic.com/v1';
        $this->defaultModel = $config['default_model'] ?? 'claude-3-sonnet-20240229';
        $this->defaultParams = $config['default_parameters'] ?? [];
    }
    
    /**
     * {@inheritDoc}
     */
    public function complete(string $prompt, string $systemPrompt = '', array $parameters = []): string
    {
        $messages = [];
        
        if (!empty($systemPrompt)) {
            $messages[] = ['role' => 'system', 'content' => $systemPrompt];
        }
        
        $messages[] = ['role' => 'user', 'content' => $prompt];
        
        return $this->chat($messages, $parameters);
    }
    
    /**
     * {@inheritDoc}
     */
    public function chat(array $messages, array $parameters = []): string
    {
        $model = $parameters['model'] ?? $this->defaultModel;
        unset($parameters['model']); // Remove from parameters as it's passed separately
        
        // Convert messages to Anthropic format if needed
        $anthropicMessages = array_map(function($message) {
            // Map 'assistant' role to 'assistant' (already correct)
            // Map 'user' role to 'user' (already correct)
            // Map 'system' role to system parameter (handled separately)
            return [
                'role' => $message['role'] === 'system' ? 'user' : $message['role'],
                'content' => $message['content']
            ];
        }, array_filter($messages, function($message) {
            return $message['role'] !== 'system';
        }));
        
        // Extract system message
        $systemMessage = '';
        foreach ($messages as $message) {
            if ($message['role'] === 'system') {
                $systemMessage = $message['content'];
                break;
            }
        }
        
        $payload = array_merge([
            'model' => $model,
            'messages' => $anthropicMessages,
        ], $this->defaultParams, $parameters);
        
        // Add system parameter if we have a system message
        if (!empty($systemMessage)) {
            $payload['system'] = $systemMessage;
        }
        
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'anthropic-version' => '2023-06-01',
            ])->post("{$this->baseUrl}/messages", $payload);
            
            if ($response->successful()) {
                return $response->json('content.0.text', '');
            } else {
                Log::error('Anthropic API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return '';
            }
        } catch (\Exception $e) {
            Log::error('Anthropic API exception', [
                'message' => $e->getMessage(),
            ]);
            return '';
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getAvailableModels(): array
    {
        return [
            'claude-3-opus-20240229',
            'claude-3-sonnet-20240229',
            'claude-3-haiku-20240307',
            'claude-2.1',
            'claude-2.0',
        ];
    }
    
    /**
     * {@inheritDoc}
     */
    public function getProviderId(): string
    {
        return 'anthropic';
    }
}