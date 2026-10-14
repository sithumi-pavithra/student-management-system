<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        if (Schema::hasColumn('students', 'status')) {
            return;
        }

        Schema::table('students', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive', 'graduated', 'suspended'])
                  ->default('active')
                  ->after('course');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};