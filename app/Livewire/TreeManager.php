<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Tree;

class TreeManager extends Component
{
    public array $nodes = [];
    public ?int $parent_id = null;
    public string $name = '';

    public function mount(): void
    {
        $this->loadNodes();
    }

    public function loadNodes(): void
    {
        $this->nodes = Tree::orderBy('path')->get()->toArray();
    }

    public function createNode(): void
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'integer', 'exists:trees,id'],
        ]);

        if ($data['parent_id']) {
            $parent = Tree::findOrFail($data['parent_id']);
            $data['depth'] = $parent->depth + 1;
        } else {
            $data['depth'] = 0;
        }

        $data['path'] = '';
        $node = Tree::create($data);

        $node->path = $node->parent_id
            ? ($parent->path . '.' . $node->id)
            : (string) $node->id;
        $node->save();

        $this->reset(['name', 'parent_id']);
        $this->loadNodes();
    }

    public function moveNode(int $id, ?int $newParentId): void
    {
        app(\App\Http\Controllers\Api\TreeController::class)
            ->move(request()->merge(['parent_id' => $newParentId])->instance(), $id);
        $this->loadNodes();
    }

    public function deleteNode(int $id): void
    {
        app(\App\Http\Controllers\Api\TreeController::class)
            ->destroy($id);
        $this->loadNodes();
    }

    public function render()
    {
        return view('livewire.tree-manager');
    }
}