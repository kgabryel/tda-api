<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksTableUpdate extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', static function(Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tasks', static function(Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
}
