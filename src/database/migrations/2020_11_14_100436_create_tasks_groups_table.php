<?php

use App\Shared\Application\IntervalType;
use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TasksGroupsCreated extends Migration
{
    public function up(): void
    {
        Schema::create('tasks_groups', static function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', Config::TASK_NAME_LENGTH);
            $table->date('start');
            $table->date('stop')->nullable();
            $table->text('content')->nullable();
            $table->unsignedInteger('interval')->nullable();
            $table->enum('interval_type', IntervalType::getValues())->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks_groups');
    }
}
