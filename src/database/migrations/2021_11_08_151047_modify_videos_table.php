<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyVideosTable extends Migration
{
    public function up(): void
    {
        Schema::table('videos', static function(Blueprint $table) {
            $table->boolean('watched')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('videos', static function(Blueprint $table) {
            $table->dropColumn('watched');
        });
    }
}
