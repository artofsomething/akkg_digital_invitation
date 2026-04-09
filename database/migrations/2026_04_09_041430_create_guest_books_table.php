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
        Schema::create('guest_books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invitation_id');
            $table->string('guest_name');
            $table->string('guest_message',500);
            $table->boolean('is_approved')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('invitation_id')
                    ->references('id')
                    ->on('invitations')
                    ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_books');
    }
};
