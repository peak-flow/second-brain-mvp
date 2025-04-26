<?php
// tests/Unit/TaskPlannerServiceTest.php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\AgentPrompt;
use App\Services\AI\AIManager;
use App\Services\TaskPlannerService;
use Illuminate\Support\Facades\Http;
use Mockery;

// Legacy tests
it('parses lined enumeration into steps array', function () {
    $aiManager = Mockery::mock(AIManager::class);
    $service = new TaskPlannerService($aiManager);
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('parseSteps');
    $method->setAccessible(true);
    $text = "1. First step\n2. Second step\n3. Third step";
    $steps = $method->invokeArgs($service, [$text]);
    expect($steps)->toBe(['First step', 'Second step', 'Third step']);
});

it('returns full text if no numbered lines', function () {
    $aiManager = Mockery::mock(AIManager::class);
    $service = new TaskPlannerService($aiManager);
    $reflection = new ReflectionClass($service);
    $method = $reflection->getMethod('parseSteps');
    $method->setAccessible(true);
    $text = "Just some random text without steps.";
    $steps = $method->invokeArgs($service, [$text]);
    expect($steps)->toBe([$text]);
});

// New tests using AIManager mock
it('uses AIManager to fetch task breakdown', function () {
    $aiManager = Mockery::mock(AIManager::class);
    $aiManager->shouldReceive('completeWithAgent')
        ->with('task_planner', "Break down the following task into atomic steps:\n\nTest")
        ->once()
        ->andReturn("1) A\n2) B");
        
    $service = new TaskPlannerService($aiManager);
    $steps = $service->breakdown('Test');
    expect($steps)->toBe(['A', 'B']);
});

it('generates a title for a task', function () {
    $aiManager = Mockery::mock(AIManager::class);
    $aiManager->shouldReceive('completeWithAgent')
        ->with('task_summarizer', "Create a concise title (5-7 words) for this task:\n\nBuild a responsive website with login")
        ->once()
        ->andReturn("Build Responsive Website With Authentication");
        
    $service = new TaskPlannerService($aiManager);
    $title = $service->generateTaskTitle("Build a responsive website with login");
    expect($title)->toBe("Build Responsive Website With Authentication");
});

it('categorizes tasks into logical groups', function () {
    $aiManager = Mockery::mock(AIManager::class);
    $tasks = [
        "Fix CSS bug",
        "Write documentation",
        "Deploy to production"
    ];
    
    $aiResponse = "Development:\n- Fix CSS bug\n- Deploy to production\n\nDocumentation:\n- Write documentation";
    
    $aiManager->shouldReceive('completeWithAgent')
        ->with('task_categorizer', Mockery::on(function($prompt) {
            return strpos($prompt, "Categorize these tasks into logical groups") === 0;
        }))
        ->once()
        ->andReturn($aiResponse);
        
    $service = new TaskPlannerService($aiManager);
    $categories = $service->categorizeTasks($tasks);
    
    expect($categories)->toHaveCount(2);
    expect($categories['Development'])->toContain('Fix CSS bug');
    expect($categories['Development'])->toContain('Deploy to production');
    expect($categories['Documentation'])->toContain('Write documentation');
});