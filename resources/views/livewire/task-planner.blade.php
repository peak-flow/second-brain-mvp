<div class="p-4">
    <h2 class="text-lg font-bold mb-4">Task Planner</h2>
    <form wire:submit.prevent="planTask" class="space-y-4">
        <div>
            <textarea wire:model="description" rows="3" placeholder="Enter your task description here..."
                class="w-full border p-2"></textarea>
            @error('description') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>
        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2">Plan Task</button>
        </div>
    </form>
    @if($steps)
        <div class="mt-4">
            <h3 class="font-semibold">Planned Steps:</h3>
            <ol class="list-decimal list-inside mt-2">
                @foreach($steps as $step)
                    <li class="mb-1">{{ $step }}</li>
                @endforeach
            </ol>
        </div>
    @endif
</div>