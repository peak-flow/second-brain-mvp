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
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'prompt' => $this->faker->paragraph(),
        ];
    }
}