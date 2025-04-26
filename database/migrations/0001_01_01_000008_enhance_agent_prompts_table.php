<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('agent_prompts', function (Blueprint $table) {
            // Add provider column with default
            $table->string('provider', 50)->default('openai')->after('prompt');
            
            // Add model column with default
            $table->string('model', 50)->default('gpt-3.5-turbo')->after('provider');
            
            // Add type column to categorize agent by function
            $table->string('type', 50)->default('general')->after('model');
            
            // Add parameters as JSON for customization
            $table->json('parameters')->nullable()->after('type');
            
            // Create index on type for filtering
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_prompts', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn(['provider', 'model', 'type', 'parameters']);
        });
    }
};