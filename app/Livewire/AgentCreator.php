<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\AgentPrompt;

class AgentCreator extends Component
{
    public string $name = '';
    public string $prompt = '';
    public array $agents = [];

    protected array $rules = [
        'name' => 'required|string|max:100',
        'prompt' => 'required|string',
    ];

    public function mount(): void
    {
        $this->loadAgents();
    }

    public function loadAgents(): void
    {
        $this->agents = AgentPrompt::orderBy('id', 'desc')->get()->toArray();
    }

    public function createAgent(): void
    {
        $data = $this->validate();
        AgentPrompt::create($data);

        $this->reset(['name', 'prompt']);
        $this->loadAgents();
    }

    public function render()
    {
        return view('livewire.agent-creator');
    }
}