<div>
    <h2 class="text-xl font-bold mb-4">Tree Manager</h2>

    <form wire:submit.prevent="createNode" class="mb-4">
        <input wire:model="name" type="text" placeholder="Node Name" class="border px-2 py-1" />
        <select wire:model="parent_id" class="border px-2 py-1">
            <option value="">-- Root --</option>
            @foreach ($nodes as $n)
                <option value="{{ $n['id'] }}">{{ str_repeat('--', $n['depth']) }} {{ $n['name'] }}</option>
            @endforeach
        </select>
        <button type="submit" class="ml-2 bg-blue-500 text-white px-3 py-1">Add</button>
    </form>

    <ul>
        @foreach ($nodes as $node)
            <li class="mb-2">
                <span>{{ str_repeat('--', $node['depth']) }} {{ $node['name'] }}</span>
                <button wire:click="deleteNode({{ $node['id'] }})" class="ml-2 text-red-500">Delete</button>
                <button wire:click="moveNode({{ $node['id'] }}, null)" class="ml-1 text-green-500">Make Root</button>
            </li>
        @endforeach
    </ul>
</div>