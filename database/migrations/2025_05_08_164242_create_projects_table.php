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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Ownership and team relationships
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete(); // project owner
            $table->uuid('uuid')->unique(); // for public API access or external references

            // Core content
            $table->string('name');
            $table->string('slug')->unique(); // SEO-friendly identifier
            $table->text('summary')->nullable(); // short description for previews
            $table->longText('description')->nullable(); // detailed description or markdown

            // Status tracking
            $table->enum('status', ['draft', 'planned', 'active', 'on_hold', 'completed', 'cancelled'])->default('draft');
            $table->boolean('is_featured')->default(false); // for highlighting projects

            // Time-based info
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Financials and progress (optional)
            $table->decimal('budget', 12, 2)->nullable();
            $table->unsignedTinyInteger('progress')->default(0); // 0–100

            // Metadata
            $table->json('meta')->nullable(); // for tags, attributes, or extensions
            $table->json('settings')->nullable(); // for user-defined configurations

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
