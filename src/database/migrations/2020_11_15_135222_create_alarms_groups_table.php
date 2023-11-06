<?php

use App\Shared\Application\Config\IntervalTypes;
use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlarmsGroupsCreated extends Migration
{
    public function up(): void
    {
        Schema::create('alarms_groups', static function(Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title', Config::ALARM_NAME_LENGTH);
            $table->date('start');
            $table->date('stop')->nullable();
            $table->unsignedInteger('interval')->nullable();
            $table->uuid('task_id')->nullable();
            $table->enum('interval_type', IntervalTypes::AVAILABLE_INTERVALS)->nullable();
            $table->text('content')->nullable();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('user_id');
            $table->foreign('task_id')
                ->references('id')
                ->on('tasks_groups')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alarms_groups');
    }
}
