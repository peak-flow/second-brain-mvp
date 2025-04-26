<?php

namespace App\Services\AI\Contracts;

interface AIProviderInterface
{
    /**
     * Send a completion request to the AI provider
     *
     * @param string $prompt The user prompt to send
     * @param string $systemPrompt Optional system instruction
     * @param array $parameters Optional additional parameters
     * @return string The AI response
     */
    public function complete(string $prompt, string $systemPrompt = '', array $parameters = []): string;
    
    /**
     * Send a chat completion request to the AI provider
     *
     * @param array $messages Array of message objects with role and content
     * @param array $parameters Optional additional parameters
     * @return string The AI response
     */
    public function chat(array $messages, array $parameters = []): string;
    
    /**
     * Get available models from this provider
     *
     * @return array List of available model identifiers
     */
    public function getAvailableModels(): array;
    
    /**
     * Get the provider identifier
     *
     * @return string The provider identifier
     */
    public function getProviderId(): string;
}