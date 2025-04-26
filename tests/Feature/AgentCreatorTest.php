<?php
// tests/Feature/AgentCreatorTest.php

use App\Models\AgentPrompt;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');

it('renders agent creator with no prompts', function () {
    Livewire::test(App\Livewire\AgentCreator::class)
        ->assertSee('Agent Creator');
    expect(AgentPrompt::count())->toBe(0);
});

it('creates a new agent prompt', function () {
    $name = 'TestAgent';
    $text = 'You are an expert assistant.';
    Livewire::test(App\Livewire\AgentCreator::class)
        ->set('name', $name)
        ->set('prompt', $text)
        ->call('createAgent')
        ->assertSet('name', '')
        ->assertSet('prompt', '');

    $this->assertDatabaseHas('agent_prompts', ['name' => $name, 'prompt' => $text]);
});