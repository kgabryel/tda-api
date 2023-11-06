<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyVideosTable2 extends Migration
{
    public function up(): void
    {
        Schema::table('videos', static function(Blueprint $table) {
            $table->boolean('assigned_to_dashboard')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('videos', static function(Blueprint $table) {
            $table->dropColumn('assigned_to_dashboard');
        });
    }
}
