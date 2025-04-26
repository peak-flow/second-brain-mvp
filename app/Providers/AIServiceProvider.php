<?php

namespace App\Providers;

use App\Services\AI\AIManager;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\AnthropicProvider;
use App\Services\AI\Providers\OpenAIProvider;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register AIManager as a singleton
        $this->app->singleton(AIManager::class, function ($app) {
            return new AIManager();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}