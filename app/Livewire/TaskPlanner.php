<?php
namespace App\Livewire;

use Livewire\Component;
use App\Services\TaskPlannerService;

class TaskPlanner extends Component
{
    public string $description = '';
    public array $steps = [];

    protected array $rules = [
        'description' => 'required|string',
    ];


    public function planTask(): void
    {
        $this->validate();
        // Resolve service from container
        $this->steps = app(\App\Services\TaskPlannerService::class)
            ->breakdown($this->description);
    }

    public function render()
    {
        return view('livewire.task-planner');
    }
}