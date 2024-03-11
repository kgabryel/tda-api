<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyFilesTable extends Migration
{
    public function up(): void
    {
        Schema::table('files', static function(Blueprint $table) {
            $table->boolean('assigned_to_dashboard')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('files', static function(Blueprint $table) {
            $table->dropColumn('assigned_to_dashboard');
        });
    }
}
