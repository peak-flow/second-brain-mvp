<?php
// tests/Feature/TreeApiTest.php

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');

it('returns an empty list of tree nodes', function () {
    $this->getJson('/api/trees')
        ->assertOk()
        ->assertExactJson([]);
});

it('creates a new tree node', function () {
    $project = Project::factory()->create();
    $data = [
        'parent_id' => null,
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Root Node',
    ];

    $response = $this->postJson('/api/trees', $data);

    $response
        ->assertCreated()
        ->assertJsonStructure([
            'id', 'parent_id', 'item_type', 'item_id', 'path', 'depth', 'name', 'created_at', 'updated_at'
        ])
        ->assertJsonFragment([
            'parent_id' => null,
            'item_type' => 'project',
            'item_id' => $project->id,
            'name' => 'Root Node',
            'depth' => 0,
        ]);

    $this->assertDatabaseHas('trees', [
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Root Node',
        'depth' => 0,
    ]);
});