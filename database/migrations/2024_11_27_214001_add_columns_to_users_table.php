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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('is_blocked');
            $table->string('organisation_name')->nullable()->after('phone');
            $table->string('organisation_about')->nullable()->after('organisation_name');
            $table->integer('no_employees')->nullable()->after('organisation_about');
            $table->string('social_media')->nullable()->after('no_employees');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('organisation_name');
            $table->dropColumn('organisation_about');
            $table->dropColumn('no_employees');
            $table->dropColumn('social_media');
        });
    }
};
