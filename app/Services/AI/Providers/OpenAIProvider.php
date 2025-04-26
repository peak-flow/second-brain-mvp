<?php

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIProvider implements AIProviderInterface
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
        $config = config('services.ai_providers.openai', []);
        $this->apiKey = $config['api_key'] ?? '';
        $this->baseUrl = $config['base_url'] ?? 'https://api.openai.com/v1';
        $this->defaultModel = $config['default_model'] ?? 'gpt-3.5-turbo';
        $this->defaultParams = $config['default_parameters'] ?? [];
    }
    
    /**
     * {@inheritDoc}
     */
    public function complete(string $prompt, string $systemPrompt = '', array $parameters = []): string
    {
        // OpenAI doesn't have a traditional completion endpoint anymore, so we'll use chat
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
        
        $payload = array_merge([
            'model' => $model,
            'messages' => $messages,
        ], $this->defaultParams, $parameters);
        
        try {
            $response = Http::withToken($this->apiKey)
                ->post("{$this->baseUrl}/chat/completions", $payload);
            
            if ($response->successful()) {
                return $response->json('choices.0.message.content', '');
            } else {
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return '';
            }
        } catch (\Exception $e) {
            Log::error('OpenAI API exception', [
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
            'gpt-3.5-turbo',
            'gpt-4',
            'gpt-4-turbo',
        ];
    }
    
    /**
     * {@inheritDoc}
     */
    public function getProviderId(): string
    {
        return 'openai';
    }
}