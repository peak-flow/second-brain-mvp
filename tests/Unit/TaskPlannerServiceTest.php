<?php
// tests/Unit/TaskPlannerServiceTest.php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


use App\Services\TaskPlannerService;
use Illuminate\Support\Facades\Http;

it('parses lined enumeration into steps array', function () {
    $service = new TaskPlannerService();
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('parseSteps');
    $method->setAccessible(true);
    $text = "1. First step\n2. Second step\n3. Third step";
    $steps = $method->invokeArgs($service, [$text]);
    expect($steps)->toBe(['First step', 'Second step', 'Third step']);
});

it('returns full text if no numbered lines', function () {
    $service = new TaskPlannerService();
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('parseSteps');
    $method->setAccessible(true);
    $text = "Just some random text without steps.";
    $steps = $method->invokeArgs($service, [$text]);
    expect($steps)->toBe([$text]);
});

it('calls OpenAI API and returns breakdown', function () {
    Http::fake([
        'https://api.openai.com/*' => Http::response(
            [
                'choices' => [
                    [
                        'message' => ['content' => "1) A\n2) B"],
                    ],
                ],
            ],
            200
        ),
    ]);
    $service = new TaskPlannerService();
    $steps = $service->breakdown('Test');
    expect($steps)->toBe(['A', 'B']);
});