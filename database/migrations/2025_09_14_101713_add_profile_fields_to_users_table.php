<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Personal information
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('username')->unique()->nullable()->after('email');
            $table->string('google_id')->nullable()->after('username');

            // Personal details
            $table->date('date_of_birth')->nullable()->after('google_id');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('date_of_birth');
            $table->string('phone')->nullable()->after('gender');

            // Physical information
            $table->decimal('height', 5, 2)->nullable()->after('phone'); // in cm
            $table->decimal('weight', 5, 2)->nullable()->after('height'); // in kg
            $table->enum('activity_level', [
                'sedentary',
                'light',
                'moderate',
                'very_active',
                'extremely_active'
            ])->nullable()->after('weight');

            // Profile
            $table->string('profile_photo_path')->nullable()->after('activity_level');

            // Make password nullable for Google users
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'username',
                'google_id',
                'date_of_birth',
                'gender',
                'phone',
                'height',
                'weight',
                'activity_level',
                'profile_photo_path'
            ]);

            // Make password required again
            $table->string('password')->nullable(false)->change();
        });
    }
};