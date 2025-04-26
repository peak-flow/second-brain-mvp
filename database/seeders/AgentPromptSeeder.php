<?php

namespace Database\Seeders;

use App\Models\AgentPrompt;
use Illuminate\Database\Seeder;

class AgentPromptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default Task Planner agent for OpenAI
        AgentPrompt::create([
            'name' => 'Task Planner (OpenAI)',
            'prompt' => 'You are a helpful assistant that breaks down tasks into atomic steps. Make each step specific and actionable. Return only the steps as a numbered list without additional explanations.',
            'provider' => 'openai',
            'model' => 'gpt-3.5-turbo',
            'type' => 'task_planner',
            'parameters' => [
                'temperature' => 0.7,
                'max_tokens' => 500,
            ],
        ]);
        
        // Create Task Summarizer agent
        AgentPrompt::create([
            'name' => 'Task Summarizer (OpenAI)',
            'prompt' => 'You are a helpful assistant that creates concise titles for tasks. Keep titles under 7 words and make them clear and descriptive.',
            'provider' => 'openai',
            'model' => 'gpt-3.5-turbo',
            'type' => 'task_summarizer',
            'parameters' => [
                'temperature' => 0.5,
                'max_tokens' => 50,
            ],
        ]);
        
        // Create Task Categorizer agent
        AgentPrompt::create([
            'name' => 'Task Categorizer (OpenAI)',
            'prompt' => 'You are a helpful assistant that categorizes tasks into logical groups. Group tasks by theme, project, or priority.',
            'provider' => 'openai',
            'model' => 'gpt-3.5-turbo',
            'type' => 'task_categorizer',
            'parameters' => [
                'temperature' => 0.6,
                'max_tokens' => 800,
            ],
        ]);
        
        // Create default Task Planner agent for Anthropic
        if (config('services.ai_providers.anthropic.api_key')) {
            AgentPrompt::create([
                'name' => 'Task Planner (Claude)',
                'prompt' => 'You are a helpful assistant that breaks down tasks into atomic steps. Make each step specific and actionable. Return only the steps as a numbered list without additional explanations.',
                'provider' => 'anthropic',
                'model' => 'claude-3-haiku-20240307',
                'type' => 'task_planner',
                'parameters' => [
                    'temperature' => 0.7,
                    'max_tokens' => 500,
                ],
            ]);
        }
    }
}