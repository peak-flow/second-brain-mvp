<?php
// tests/Feature/TreeManagerTest.php

use App\Models\Tree;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');

it('renders tree manager with no nodes', function () {
    Livewire::test(App\Livewire\TreeManager::class)
        ->assertSet('nodes', []);
});

it('can create a root node', function () {
    $component = Livewire::test(App\Livewire\TreeManager::class)
        ->set('name', 'RootA')
        ->call('createNode')
        ->assertSet('name', '')
        ->assertSet('parent_id', null);

    $component->assertSee('RootA');
    expect(Tree::count())->toBe(1);
});

it('can delete a node', function () {
    // Create a node directly with required fields
    $tree = Tree::create([
        'name' => 'ToDelete',
        'parent_id' => null,
        'depth' => 0,
        'path' => '',
        'item_type' => 'tree',
        'item_id' => 0,
    ]);
    $tree->path = (string) $tree->id;
    $tree->save();

    Livewire::test(App\Livewire\TreeManager::class)
        ->call('deleteNode', $tree->id);

    expect(Tree::count())->toBe(0);
});