<?php
namespace Database\Factories;

use App\Models\AgentPrompt;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgentPromptFactory extends Factory
{
    /**
     * The model the factory corresponds to.
     *
     * @var string
     */
    protected $model = AgentPrompt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers = ['openai', 'anthropic'];
        $types = ['general', 'task_planner', 'task_summarizer', 'task_categorizer', 'note_taker', 'research', 'coding'];
        
        return [
            'name' => $this->faker->word() . ' ' . $this->faker->word(),
            'prompt' => $this->faker->paragraph(),
            'provider' => $this->faker->randomElement($providers),
            'model' => $this->faker->randomElement(['gpt-3.5-turbo', 'gpt-4', 'claude-3-haiku']),
            'type' => $this->faker->randomElement($types),
            'parameters' => [
                'temperature' => $this->faker->randomFloat(1, 0.1, 1.0),
                'max_tokens' => $this->faker->numberBetween(50, 2000),
            ],
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function openai()
    {
        return $this->state(function (array $attributes) {
            return [
                'provider' => 'openai',
                'model' => $this->faker->randomElement(['gpt-3.5-turbo', 'gpt-4']),
            ];
        });
    }

    /**
     * Configure the model factory for Anthropic.
     *
     * @return $this
     */
    public function anthropic()
    {
        return $this->state(function (array $attributes) {
            return [
                'provider' => 'anthropic',
                'model' => $this->faker->randomElement(['claude-3-sonnet-20240229', 'claude-3-haiku-20240307']),
            ];
        });
    }

    /**
     * Configure the model factory for task planning.
     *
     * @return $this
     */
    public function taskPlanner()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Task Planner',
                'prompt' => 'You are a helpful assistant that breaks down tasks into atomic steps. Make each step specific and actionable.',
                'type' => 'task_planner',
            ];
        });
    }
}