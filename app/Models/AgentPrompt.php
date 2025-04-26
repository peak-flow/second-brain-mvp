<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentPrompt extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'prompt',
        'provider',
        'model',
        'type',
        'parameters',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'parameters' => 'array',
    ];

    /**
     * Get the default value for parameters when none provided
     *
     * @return array<string, mixed>
     */
    public function getDefaultParameters(): array
    {
        $provider = $this->provider ?? 'openai';
        return config("services.ai_providers.{$provider}.default_parameters", []);
    }

    /**
     * Get the combined parameters (defaults + custom)
     *
     * @return array<string, mixed>
     */
    public function getCombinedParameters(): array
    {
        return array_merge(
            $this->getDefaultParameters(),
            $this->parameters ?? []
        );
    }

    /**
     * Scope a query to only include agents of a specific type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}