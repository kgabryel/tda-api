<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTasksGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::table('tasks_groups', static function(Blueprint $table) {
            $table->boolean('active')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('tasks_groups', static function(Blueprint $table) {
            $table->dropColumn('active');
        });
    }
}
