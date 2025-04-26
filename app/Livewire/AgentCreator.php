<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\AgentPrompt;
use App\Services\AI\AIManager;

class AgentCreator extends Component
{
    public string $name = '';
    public string $prompt = '';
    public string $provider = 'openai';
    public string $model = 'gpt-3.5-turbo';
    public string $type = 'general';
    public ?array $parameters = null;
    public array $agents = [];
    public array $providers = [];
    public array $models = [];
    public array $agentTypes = [
        'general' => 'General Purpose',
        'task_planner' => 'Task Planning',
        'task_summarizer' => 'Task Summarization',
        'task_categorizer' => 'Task Categorization',
        'note_taker' => 'Note Taking',
        'research' => 'Research',
        'coding' => 'Coding Assistant',
    ];

    protected array $rules = [
        'name' => 'required|string|max:100',
        'prompt' => 'required|string',
        'provider' => 'required|string|max:50',
        'model' => 'required|string|max:50',
        'type' => 'required|string|max:50',
        'parameters' => 'nullable|array',
    ];
    
    public function mount(AIManager $aiManager): void
    {
        // Get available providers
        $this->providers = array_keys($aiManager->getProviders());
        
        // Set default provider
        if (!empty($this->providers)) {
            $this->provider = $this->providers[0];
            $this->updateModels($aiManager);
        }
        
        $this->loadAgents();
    }
    
    public function updateModels(AIManager $aiManager): void
    {
        $provider = $aiManager->getProvider($this->provider);
        if ($provider) {
            $this->models = $provider->getAvailableModels();
            if (!empty($this->models)) {
                $this->model = $this->models[0];
            }
        }
    }

    public function loadAgents(): void
    {
        $this->agents = AgentPrompt::orderBy('id', 'desc')->get()->toArray();
    }

    public function createAgent(): void
    {
        $data = $this->validate();
        
        // If parameters is null, set it to an empty array
        if (is_null($data['parameters'])) {
            $data['parameters'] = [];
        }
        
        AgentPrompt::create($data);

        $this->reset(['name', 'prompt', 'parameters']);
        $this->loadAgents();
    }
    
    public function setProviderChanged(): void
    {
        $this->updateModels(app(AIManager::class));
    }
    
    public function deleteAgent($id): void
    {
        $agent = AgentPrompt::find($id);
        if ($agent) {
            $agent->delete();
            $this->loadAgents();
        }
    }

    public function render()
    {
        return view('livewire.agent-creator');
    }
}