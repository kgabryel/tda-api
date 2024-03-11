<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlarmsCreated extends Migration
{
    public function up(): void
    {
        Schema::create('alarms', static function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', Config::ALARM_NAME_LENGTH);
            $table->text('content')->nullable();
            $table->boolean('checked');
            $table->date('date')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->uuid('group_id')->nullable();
            $table->uuid('task_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('group_id')
                ->references('id')
                ->on('alarms_groups')
                ->onDelete('cascade');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarms');
    }
}
