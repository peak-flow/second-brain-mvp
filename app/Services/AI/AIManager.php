<?php

namespace App\Services\AI;

use App\Models\AgentPrompt;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\AnthropicProvider;
use App\Services\AI\Providers\OpenAIProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AIManager
{
    /**
     * @var array Provider instances
     */
    protected array $providers = [];
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Register default providers
        $this->registerProvider(new OpenAIProvider());
        $this->registerProvider(new AnthropicProvider());
    }
    
    /**
     * Register a provider
     *
     * @param AIProviderInterface $provider
     * @return void
     */
    public function registerProvider(AIProviderInterface $provider): void
    {
        $this->providers[$provider->getProviderId()] = $provider;
    }
    
    /**
     * Get a provider instance
     *
     * @param string $providerId
     * @return AIProviderInterface|null
     */
    public function getProvider(string $providerId): ?AIProviderInterface
    {
        return $this->providers[$providerId] ?? null;
    }
    
    /**
     * Get all registered providers
     *
     * @return array<string, AIProviderInterface>
     */
    public function getProviders(): array
    {
        return $this->providers;
    }
    
    /**
     * Execute a completion request using a specific agent
     *
     * @param string $agentType The type of agent to use (e.g., 'task_planner')
     * @param string $prompt The user prompt
     * @param array $parameters Additional parameters
     * @return string The AI response
     */
    public function completeWithAgent(string $agentType, string $prompt, array $parameters = []): string
    {
        // Get the agent, preferring active ones first
        $agent = AgentPrompt::ofType($agentType)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if (!$agent) {
            Log::warning("No agent of type '{$agentType}' found. Using default OpenAI.");
            $provider = $this->getProvider('openai');
            $systemPrompt = '';
            $model = 'gpt-3.5-turbo';
        } else {
            $providerId = $agent->provider;
            $provider = $this->getProvider($providerId);
            $systemPrompt = $agent->prompt;
            $model = $agent->model;
            $parameters = array_merge($agent->getCombinedParameters(), $parameters);
        }
        
        if (!$provider) {
            Log::error("Provider '{$providerId}' not found or not registered.");
            return '';
        }
        
        // Override model if specified in agent
        if (!empty($model)) {
            $parameters['model'] = $model;
        }
        
        // Execute the completion
        return $provider->complete($prompt, $systemPrompt, $parameters);
    }
    
    /**
     * Execute a chat request using a specific agent
     *
     * @param string $agentType The type of agent to use
     * @param array $messages The messages
     * @param array $parameters Additional parameters
     * @return string The AI response
     */
    public function chatWithAgent(string $agentType, array $messages, array $parameters = []): string
    {
        // Get the agent
        $agent = AgentPrompt::ofType($agentType)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if (!$agent) {
            Log::warning("No agent of type '{$agentType}' found. Using default OpenAI.");
            $provider = $this->getProvider('openai');
            $model = 'gpt-3.5-turbo';
        } else {
            $providerId = $agent->provider;
            $provider = $this->getProvider($providerId);
            $model = $agent->model;
            $parameters = array_merge($agent->getCombinedParameters(), $parameters);
            
            // Insert system message if agent has a prompt
            if (!empty($agent->prompt)) {
                // Check if messages already has a system message
                $hasSystem = false;
                foreach ($messages as $message) {
                    if ($message['role'] === 'system') {
                        $hasSystem = true;
                        break;
                    }
                }
                
                if (!$hasSystem) {
                    array_unshift($messages, [
                        'role' => 'system',
                        'content' => $agent->prompt
                    ]);
                }
            }
        }
        
        if (!$provider) {
            Log::error("Provider '{$providerId}' not found or not registered.");
            return '';
        }
        
        // Override model if specified in agent
        if (!empty($model)) {
            $parameters['model'] = $model;
        }
        
        // Execute the chat
        return $provider->chat($messages, $parameters);
    }
    
    /**
     * Direct completion with a specified provider
     *
     * @param string $providerId The provider ID
     * @param string $prompt The user prompt
     * @param string $systemPrompt Optional system instruction
     * @param array $parameters Additional parameters
     * @return string The AI response
     */
    public function complete(string $providerId, string $prompt, string $systemPrompt = '', array $parameters = []): string
    {
        $provider = $this->getProvider($providerId);
        
        if (!$provider) {
            Log::error("Provider '{$providerId}' not found or not registered.");
            return '';
        }
        
        return $provider->complete($prompt, $systemPrompt, $parameters);
    }
    
    /**
     * Direct chat with a specified provider
     *
     * @param string $providerId The provider ID
     * @param array $messages The messages
     * @param array $parameters Additional parameters
     * @return string The AI response
     */
    public function chat(string $providerId, array $messages, array $parameters = []): string
    {
        $provider = $this->getProvider($providerId);
        
        if (!$provider) {
            Log::error("Provider '{$providerId}' not found or not registered.");
            return '';
        }
        
        return $provider->chat($messages, $parameters);
    }
}