<?php
namespace App\Services;

use App\Services\AI\AIManager;
use Illuminate\Support\Facades\Log;

class TaskPlannerService
{
    /**
     * @var AIManager
     */
    protected AIManager $aiManager;
    
    /**
     * Constructor
     *
     * @param AIManager $aiManager
     */
    public function __construct(AIManager $aiManager)
    {
        $this->aiManager = $aiManager;
    }
    
    /**
     * Break down a task description into atomic steps using AI.
     *
     * @param string $task The task description
     * @param string|null $agentType Optional agent type override
     * @return string[] Array of steps
     */
    public function breakdown(string $task, string $agentType = 'task_planner'): array
    {
        try {
            // Try to use a specific agent type for task planning
            $prompt = "Break down the following task into atomic steps:\n\n{$task}";
            $content = $this->aiManager->completeWithAgent($agentType, $prompt);
            
            if (empty($content)) {
                Log::warning("Empty response from AI for task planning. Task: " . substr($task, 0, 50) . "...");
                return [];
            }
            
            return $this->parseSteps($content);
        } catch (\Exception $e) {
            Log::error("Error breaking down task: " . $e->getMessage(), [
                'task' => $task,
                'agent_type' => $agentType
            ]);
            return [];
        }
    }

    /**
     * Parse the AI response into step array.
     *
     * @param string $text
     * @return string[]
     */
    protected function parseSteps(string $text): array
    {
        $lines = preg_split('/\r?\n/', $text) ?: [];
        $steps = [];
        foreach ($lines as $line) {
            $trim = trim($line);
            if (preg_match('/^(?:\d+[\.)]|-)\s*(.+)$/', $trim, $m)) {
                $steps[] = trim($m[1]);
            }
        }
        if (empty($steps) && trim($text) !== '') {
            $steps[] = trim($text);
        }
        return $steps;
    }
    
    /**
     * Summarize a task with AI
     *
     * @param string $description The full task description
     * @param string|null $agentType Optional agent type override
     * @return string The summarized title
     */
    public function generateTaskTitle(string $description, string $agentType = 'task_summarizer'): string
    {
        try {
            $prompt = "Create a concise title (5-7 words) for this task:\n\n{$description}";
            $content = $this->aiManager->completeWithAgent($agentType, $prompt);
            
            return trim($content);
        } catch (\Exception $e) {
            Log::error("Error generating task title: " . $e->getMessage());
            // Fall back to a truncated version of the description
            return substr($description, 0, 40) . (strlen($description) > 40 ? '...' : '');
        }
    }
    
    /**
     * Organize tasks into categories with AI
     *
     * @param array $tasks Array of task descriptions
     * @param string|null $agentType Optional agent type override
     * @return array Categorized tasks [category => [task1, task2, ...]]
     */
    public function categorizeTasks(array $tasks, string $agentType = 'task_categorizer'): array
    {
        if (empty($tasks)) {
            return [];
        }
        
        try {
            $taskList = implode("\n- ", array_map('trim', $tasks));
            $prompt = "Categorize these tasks into logical groups:\n\n- {$taskList}\n\nGroup them in this format:\n\nCategory 1:\n- Task 1\n- Task 2\n\nCategory 2:\n- Task 3";
            
            $content = $this->aiManager->completeWithAgent($agentType, $prompt);
            
            return $this->parseCategories($content, $tasks);
        } catch (\Exception $e) {
            Log::error("Error categorizing tasks: " . $e->getMessage());
            // Fall back to a single "Uncategorized" category
            return ['Uncategorized' => $tasks];
        }
    }
    
    /**
     * Parse category results from AI response
     *
     * @param string $text AI response text
     * @param array $originalTasks Original tasks for fallback
     * @return array Categorized tasks
     */
    protected function parseCategories(string $text, array $originalTasks): array
    {
        $categories = [];
        $currentCategory = 'Uncategorized';
        
        $lines = preg_split('/\r?\n/', $text) ?: [];
        
        foreach ($lines as $line) {
            $trimmedLine = trim($line);
            
            // Skip empty lines
            if (empty($trimmedLine)) {
                continue;
            }
            
            // Detect category headers (ends with colon)
            if (preg_match('/^(.+):$/', $trimmedLine, $matches)) {
                $currentCategory = trim($matches[1]);
                if (!isset($categories[$currentCategory])) {
                    $categories[$currentCategory] = [];
                }
            }
            // Detect list items
            elseif (preg_match('/^(?:\d+[\.)]|-)\s*(.+)$/', $trimmedLine, $matches)) {
                $categories[$currentCategory][] = trim($matches[1]);
            }
        }
        
        // If parsing failed completely, return all under Uncategorized
        if (empty($categories)) {
            return ['Uncategorized' => $originalTasks];
        }
        
        return $categories;
    }
}