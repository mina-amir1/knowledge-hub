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
            if (Schema::hasColumn('users', 'organisation_name')) {
                $table->dropColumn('organisation_name');
            }
            if (Schema::hasColumn('users', 'organisation_about')) {
                $table->dropColumn('organisation_about');
            }
            if (Schema::hasColumn('users', 'social_media')) {
                $table->dropColumn('social_media');
            }
            if (Schema::hasColumn('users', 'no_employees')) {
                $table->dropColumn('no_employees');
            }
            $table->boolean('super_admin')->default(false);
            $table->foreignId('organization_id')->after('phone')->nullable()->constrained('organizations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
        public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the foreign key constraint and drop the `organization_id` column
            $table->dropForeign(['organization_id']);
            $table->dropColumn('organization_id');

            // Re-add the columns that were dropped in the `up` method
            $table->string('organisation_name')->nullable()->after('is_blocked');
            $table->text('organisation_about')->nullable()->after('organisation_name');
            $table->string('social_media')->nullable()->after('organisation_about');
            $table->integer('no_employees')->nullable()->after('organisation_about');
            $table->dropColumn('super_admin');
        });
    }

};
