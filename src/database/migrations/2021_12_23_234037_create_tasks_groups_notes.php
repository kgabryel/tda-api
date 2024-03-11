<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksGroupsNotes extends Migration
{
    public function up(): void
    {
        Schema::create('tasks_groups_notes', static function(Blueprint $table) {
            $table->integer('note_id')->unsigned();
            $table->uuid('task_id');
            $table->foreign('note_id')
                ->references('id')
                ->on('notes')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks_groups')
                ->onDelete('cascade');
            $table->primary(['note_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks_groups_notes');
    }
}
