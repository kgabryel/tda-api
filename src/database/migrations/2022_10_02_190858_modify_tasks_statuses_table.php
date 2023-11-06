<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTasksStatusesTable extends Migration
{
    public function up(): void
    {
        Schema::table('tasks_statuses', static function(Blueprint $table) {
            $table->integer('status_order')->unsigned()->default(1);
        });
    }

    public function down(): void
    {
        Schema::table('tasks_statuses', static function(Blueprint $table) {
            $table->dropColumn('status_order');
        });
    }
}
