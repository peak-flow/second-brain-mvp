<?php
// tests/Feature/TreeApiTest.php

use App\Models\Project;
use App\Models\Tree;
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

it('moves a node to another parent', function () {
    // Setup: two root nodes and one child under first root
    $project = App\Models\Project::factory()->create();
    $root1 = $this->postJson('/api/trees', [
        'parent_id' => null,
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Root One',
    ])->json();
    $root2 = $this->postJson('/api/trees', [
        'parent_id' => null,
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Root Two',
    ])->json();

    $child = $this->postJson('/api/trees', [
        'parent_id' => $root1['id'],
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Child Node',
    ])->json();

    // Move child from root1 to root2
    $response = $this->putJson("/api/trees/{$child['id']}/move", [
        'parent_id' => $root2['id'],
    ]);

    $response->assertOk();

    // Verify in database
    $moved = App\Models\Tree::find($child['id']);
    expect($moved->parent_id)->toBe($root2['id']);
    expect($moved->depth)->toBe(1);
    expect($moved->path)->toBe($root2['id'] . '.' . $child['id']);
});

it('deletes a node and its descendants', function () {
    $project = App\Models\Project::factory()->create();
    $root = $this->postJson('/api/trees', [
        'parent_id' => null,
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Root',
    ])->json();
    $child = $this->postJson('/api/trees', [
        'parent_id' => $root['id'],
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Child',
    ])->json();

    // Delete root should remove both root and child
    $this->deleteJson("/api/trees/{$root['id']}")
         ->assertNoContent();

    $this->assertDatabaseMissing('trees', ['id' => $root['id']]);
    $this->assertDatabaseMissing('trees', ['id' => $child['id']]);
});

it('fetches the full subtree of a node', function () {
    $project = App\Models\Project::factory()->create();
    // root
    $root = $this->postJson('/api/trees', [
        'parent_id' => null,
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Root',
    ])->json();
    // child
    $child = $this->postJson('/api/trees', [
        'parent_id' => $root['id'],
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Child',
    ])->json();
    // grandchild
    $grand = $this->postJson('/api/trees', [
        'parent_id' => $child['id'],
        'item_type' => 'project',
        'item_id' => $project->id,
        'name' => 'Grandchild',
    ])->json();

    // fetch subtree
    $response = $this->getJson("/api/trees/{$root['id']}/subtree");
    $response->assertOk();
    $data = $response->json();
    // should contain root, child, grandchild
    $ids = array_column($data, 'id');
    expect($ids)->toContain($root['id'])->toContain($child['id'])->toContain($grand['id']);
});

it('fetches a depth-limited subtree', function () {
    $project = App\Models\Project::factory()->create();
    $root = Tree::create([ 'parent_id' => null, 'item_type' => 'project', 'item_id' => $project->id, 'name' => 'Root', 'depth' => 0, 'path' => '' ]);
    $root->path = (string)$root->id; $root->save();
    $child = Tree::create([ 'parent_id' => $root->id, 'item_type' => 'project', 'item_id' => $project->id, 'name' => 'Child', 'depth' => 1, 'path' => $root->id . '.' . 2 ]);
    $grand = Tree::create([ 'parent_id' => 2, 'item_type' => 'project', 'item_id' => $project->id, 'name' => 'Grandchild', 'depth' => 2, 'path' => $root->id . '.2.3' ]);

    // depth = 1 => should only include root and child
    $response = $this->getJson("/api/trees/{$root->id}/subtree?depth=1");
    $response->assertOk();
    $data = $response->json();
    $ids = array_column($data, 'id');
    expect($ids)->toContain($root->id)->toContain($child->id);
    expect($ids)->not->toContain($grand->id);
});