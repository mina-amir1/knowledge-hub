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
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->integer('thread_id')->nullable();
            $table->integer('user_id');
            $table->integer('comment_id')->nullable();
            $table->string('file_name');
            $table->string('original_name');
            $table->tinyInteger('status')->default(0)->comment('0:pending, 1:approved, 2:blocked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
