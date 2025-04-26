<?php
// tests/Feature/TaskApiTest.php

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');

it('returns an empty list of tasks', function () {
    $this->getJson('/api/tasks')
        ->assertOk()
        ->assertExactJson([]);
});

it('creates a new task', function () {
    $data = [
        'title' => 'Test Task',
        'description' => 'Task description',
        'status' => 'pending',
        'due_date' => now()->addDay()->toDateTimeString(),
    ];

    $response = $this->postJson('/api/tasks', $data);

    $response
        ->assertCreated()
        ->assertJsonFragment([
            'title' => $data['title'],
            'description' => $data['description'],
            'status' => $data['status'],
        ]);

    $this->assertDatabaseHas('tasks', [
        'title' => $data['title'],
        'description' => $data['description'],
        'status' => $data['status'],
    ]);
});