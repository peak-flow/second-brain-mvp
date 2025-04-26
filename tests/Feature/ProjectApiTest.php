<?php
// tests/Feature/ProjectApiTest.php
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(function () {
    // Refresh database for each test
});

it('returns an empty list of projects', function () {
    $this->getJson('/api/projects')
        ->assertOk()
        ->assertExactJson([]);
});

it('creates a new project', function () {
    $data = [
        'title' => 'Test Project',
        'description' => 'Project description',
        'status' => 'active',
    ];

    $this->postJson('/api/projects', $data)
        ->assertCreated()
        ->assertJsonFragment($data);

    $this->assertDatabaseHas('projects', $data);
});