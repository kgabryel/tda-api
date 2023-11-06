<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksCreated extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', static function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', Config::TASK_NAME_LENGTH);
            $table->text('content')->nullable();
            $table->date('date')->nullable();
            $table->uuid('parent_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();
            $table->uuid('group_id')->nullable();
            $table->foreign('group_id')
                ->references('id')
                ->on('tasks_groups')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('status_id')
                ->references('id')
                ->on('tasks_statuses')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
}
