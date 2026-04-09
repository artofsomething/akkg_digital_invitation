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
        Schema::create('invitation_sections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invitation_id');
            $table->enum('section_type',['opening','profile','the_date','gallery','map','guest_book','rsvp']);
            $table->json('content');
            $table->boolean('is_visible')->default(true);
            $table->integer('order')->default(0);
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
        Schema::dropIfExists('invitation_sections');
    }
};
