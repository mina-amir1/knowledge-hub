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
        Schema::table('attachments', function (Blueprint $table) {
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('attachments')){
            Schema::table('attachments', function (Blueprint $table) {
                $table->dropForeign(['organization_id']);
                $table->dropColumn('organization_id');
            });
        }
    }
};
