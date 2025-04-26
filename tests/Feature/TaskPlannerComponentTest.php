<?php
// tests/Feature/TaskPlannerComponentTest.php

use App\Livewire\TaskPlanner;
use App\Services\TaskPlannerService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class)->in('Feature');

it('validates description is required', function () {
    Livewire::test(TaskPlanner::class)
        ->set('description', '')
        ->call('planTask')
        ->assertHasErrors(['description' => 'required']);
});

it('plans a task and displays steps', function () {
    $fake = Mockery::mock(TaskPlannerService::class);
    $fake->shouldReceive('breakdown')
        ->once()
        ->with('Test task')
        ->andReturn(['Step A', 'Step B']);
    app()->instance(TaskPlannerService::class, $fake);

    Livewire::test(TaskPlanner::class)
        ->set('description', 'Test task')
        ->call('planTask')
        ->assertSet('steps', ['Step A', 'Step B'])
        ->assertSee('Step A')
        ->assertSee('Step B');
});