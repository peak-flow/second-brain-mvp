@php use Illuminate\Support\Str; @endphp
<div class="p-4">
    <h2 class="text-lg font-bold mb-4">Agent Creator</h2>

    <form wire:submit.prevent="createAgent" class="space-y-4">
        <div>
            <input wire:model="name" type="text" placeholder="Agent Name" class="w-full border p-2" />
            @error('name') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>
        <div>
            <textarea wire:model="prompt" rows="4" placeholder="Enter agent prompt here..."
                class="w-full border p-2"></textarea>
            @error('prompt') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>
        <div>
            <button type="submit" class="bg-green-500 text-white px-4 py-2">Create Agent</button>
        </div>
    </form>

    @if($agents)
        <div class="mt-6">
            <h3 class="font-semibold">Saved Agents</h3>
            <ul class="list-disc list-inside mt-2">
                @foreach($agents as $agent)
                    <li class="mb-1">
                        <strong>{{ $agent['name'] }}:</strong> {{ Str::limit($agent['prompt'], 100) }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>