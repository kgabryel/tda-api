<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksVideosTable extends Migration
{
    public function up(): void
    {
        Schema::create('tasks_videos', static function(Blueprint $table) {
            $table->integer('video_id')
                ->unsigned();
            $table->uuid('task_id');
            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');
            $table->primary(['video_id', 'task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks_videos');
    }
}
