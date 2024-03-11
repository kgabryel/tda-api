<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksStatusesCreated extends Migration
{
    public function up(): void
    {
        Schema::create('tasks_statuses', static function(Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('color', 9);
            $table->string('icon', 100);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks_statuses');
    }
}
