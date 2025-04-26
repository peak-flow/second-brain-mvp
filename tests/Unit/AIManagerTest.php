<?php

namespace Tests\Unit;

use App\Models\AgentPrompt;
use App\Services\AI\AIManager;
use App\Services\AI\Contracts\AIProviderInterface;
use App\Services\AI\Providers\OpenAIProvider;
use App\Services\AI\Providers\AnthropicProvider;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AIManagerTest extends TestCase
{
    use RefreshDatabase;
    
    protected AIManager $manager;
    protected $openAIMock;
    protected $anthropicMock;
    
    public function setUp(): void
    {
        parent::setUp();
        
        // Create mock providers
        $this->openAIMock = Mockery::mock(OpenAIProvider::class);
        $this->openAIMock->shouldReceive('getProviderId')->andReturn('openai');
        
        $this->anthropicMock = Mockery::mock(AnthropicProvider::class);
        $this->anthropicMock->shouldReceive('getProviderId')->andReturn('anthropic');
        
        // Create AIManager and register mock providers
        $this->manager = new AIManager();
        $this->manager->registerProvider($this->openAIMock);
        $this->manager->registerProvider($this->anthropicMock);
    }
    
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function test_get_provider(): void
    {
        $provider = $this->manager->getProvider('openai');
        $this->assertSame($this->openAIMock, $provider);
        
        $provider = $this->manager->getProvider('anthropic');
        $this->assertSame($this->anthropicMock, $provider);
        
        $provider = $this->manager->getProvider('nonexistent');
        $this->assertNull($provider);
    }
    
    public function test_get_providers(): void
    {
        $providers = $this->manager->getProviders();
        $this->assertCount(2, $providers);
        $this->assertArrayHasKey('openai', $providers);
        $this->assertArrayHasKey('anthropic', $providers);
    }
    
    public function test_complete(): void
    {
        $this->openAIMock->shouldReceive('complete')
            ->with('Hello', 'You are a helpful assistant', ['temperature' => 0.7])
            ->once()
            ->andReturn('Hi there! How can I help you?');
            
        $result = $this->manager->complete('openai', 'Hello', 'You are a helpful assistant', ['temperature' => 0.7]);
        $this->assertEquals('Hi there! How can I help you?', $result);
    }
    
    public function test_chat(): void
    {
        $messages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant'],
            ['role' => 'user', 'content' => 'Hello'],
        ];
        
        $this->anthropicMock->shouldReceive('chat')
            ->with($messages, ['temperature' => 0.8])
            ->once()
            ->andReturn('Hello! How can I assist you today?');
            
        $result = $this->manager->chat('anthropic', $messages, ['temperature' => 0.8]);
        $this->assertEquals('Hello! How can I assist you today?', $result);
    }
    
    public function test_complete_with_agent(): void
    {
        // Create agent in database
        $agent = AgentPrompt::create([
            'name' => 'Test Agent',
            'prompt' => 'You are a helpful assistant',
            'provider' => 'openai',
            'model' => 'gpt-4',
            'type' => 'test_agent',
            'parameters' => ['temperature' => 0.5],
        ]);
        
        $this->openAIMock->shouldReceive('complete')
            ->with('Hello', 'You are a helpful assistant', ['temperature' => 0.5, 'model' => 'gpt-4'])
            ->once()
            ->andReturn('Hi there! How can I help you?');
            
        $result = $this->manager->completeWithAgent('test_agent', 'Hello');
        $this->assertEquals('Hi there! How can I help you?', $result);
    }
    
    public function test_chat_with_agent(): void
    {
        // Create agent in database
        $agent = AgentPrompt::create([
            'name' => 'Test Chat Agent',
            'prompt' => 'You are a helpful assistant',
            'provider' => 'anthropic',
            'model' => 'claude-3-haiku',
            'type' => 'test_chat_agent',
            'parameters' => ['temperature' => 0.6],
        ]);
        
        $messages = [
            ['role' => 'user', 'content' => 'Hello'],
        ];
        
        // The system message should be added automatically from the agent
        $expectedMessages = [
            ['role' => 'system', 'content' => 'You are a helpful assistant'],
            ['role' => 'user', 'content' => 'Hello'],
        ];
        
        $this->anthropicMock->shouldReceive('chat')
            ->with($expectedMessages, ['temperature' => 0.6, 'model' => 'claude-3-haiku'])
            ->once()
            ->andReturn('Hello! How can I assist you today?');
            
        $result = $this->manager->chatWithAgent('test_chat_agent', $messages);
        $this->assertEquals('Hello! How can I assist you today?', $result);
    }
    
    public function test_fallback_to_default_when_no_agent_found(): void
    {
        $this->openAIMock->shouldReceive('complete')
            ->with('Hello', '', ['model' => 'gpt-3.5-turbo'])
            ->once()
            ->andReturn('Default response');
            
        $result = $this->manager->completeWithAgent('nonexistent_agent_type', 'Hello');
        $this->assertEquals('Default response', $result);
    }
}