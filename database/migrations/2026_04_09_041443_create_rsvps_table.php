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
        Schema::create('rsvps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('invitation_id');
            $table->string('guest_name');
            $table->string('guest_phone')->nullable();
            $table->enum('attendance',['yes','no','maybe']);
            $table->integer('total_person')->default(1);
            $table->text('message')->nullable();
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
        Schema::dropIfExists('rsvps');
    }
};
