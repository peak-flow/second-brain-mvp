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
        Schema::create('trees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('trees')->nullOnDelete();
            $table->text('path');
            $table->string('item_type', 50);
            $table->unsignedBigInteger('item_id');
            $table->unsignedInteger('depth');
            $table->string('name', 255);
            $table->timestamps();

            $table->index('path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trees');
    }
};