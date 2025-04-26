<?php
// tests/Feature/TopicApiTest.php

use App\Models\Topic;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class)->in('Feature');

it('returns an empty list of topics', function () {
    $this->getJson('/api/topics')
        ->assertOk()
        ->assertExactJson([]);
});

it('creates a new topic', function () {
    $data = [
        'title' => 'Test Topic',
        'content' => 'Topic content',
    ];

    $response = $this->postJson('/api/topics', $data);

    $response
        ->assertCreated()
        ->assertJsonFragment($data);

    $this->assertDatabaseHas('topics', $data);
});