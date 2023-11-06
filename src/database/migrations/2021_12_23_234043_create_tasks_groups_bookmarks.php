<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksGroupsBookmarks extends Migration
{
    public function up(): void
    {
        Schema::create('tasks_groups_bookmarks', static function(Blueprint $table) {
            $table->integer('bookmark_id')
                ->unsigned();
            $table->uuid('task_id');
            $table->foreign('bookmark_id')
                ->references('id')
                ->on('bookmarks')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks_groups')
                ->onDelete('cascade');
            $table->primary(['bookmark_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks_groups_bookmarks');
    }
}
