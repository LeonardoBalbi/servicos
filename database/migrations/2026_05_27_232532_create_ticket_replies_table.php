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
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_type', 24)->default('customer')->index();
            $table->string('author_name')->nullable();
            $table->string('author_email')->nullable();
            $table->longText('message');
            $table->longText('message_html')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->boolean('is_read_by_customer')->default(false);
            $table->boolean('is_read_by_staff')->default(false);
            $table->unsignedBigInteger('legacy_reply_id')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['ticket_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_replies');
    }
};
