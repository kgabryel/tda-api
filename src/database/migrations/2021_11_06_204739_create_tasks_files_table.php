<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksFilesTable extends Migration
{
    public function up(): void
    {
        Schema::create('tasks_files', static function(Blueprint $table) {
            $table->integer('file_id')->unsigned();
            $table->uuid('task_id');
            $table->foreign('file_id')
                ->references('id')
                ->on('files')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');
            $table->primary(['file_id', 'task_id']
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks_files');
    }
}
