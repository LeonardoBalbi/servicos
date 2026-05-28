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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('protocol')->unique();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable()->index();
            $table->string('customer_phone', 32)->nullable();
            $table->string('subject');
            $table->longText('message');
            $table->longText('message_html')->nullable();
            $table->string('status', 32)->default('new')->index();
            $table->string('priority', 24)->default('medium')->index();
            $table->string('source', 24)->default('web');
            $table->string('legacy_track_id')->nullable()->unique();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('first_response_at')->nullable();
            $table->timestamp('last_reply_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
            $table->index(['category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
