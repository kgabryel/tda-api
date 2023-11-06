<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsTasksGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::create('catalogs_tasks_groups', static function(Blueprint $table) {
            $table->unsignedBigInteger('catalog_id');
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
            $table->uuid('task_id');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks_groups')
                ->onDelete('cascade');
            $table->primary(['catalog_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogs_tasks_groups');
    }
}
