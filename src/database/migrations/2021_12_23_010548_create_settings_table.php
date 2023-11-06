<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    public function up(): void
    {
        Schema::create('settings', static function(Blueprint $table) {
            $table->id();
            $table->unsignedInteger('default_pagination')->default(10);
            $table->boolean('hide_done_tasks')->default(false);
            $table->boolean('hide_done_subtasks')->default(false);
            $table->boolean('hide_inactive_alarms')->default(false);
            $table->boolean('hide_inactive_notifications')->default(false);
            $table->boolean('hide_done_tasks_in_tasks_groups')->default(false);
            $table->boolean('hide_inactive_alarms_in_alarms_groups')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unique(['user_id']);
            Schema::table('users', static function(Blueprint $table) {
                $table->dropColumn('default_pagination');
                $table->dropColumn('hide_done_tasks');
                $table->dropColumn('hide_inactive_alarms');
            });
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::table('users', static function(Blueprint $table) {
            $table->unsignedInteger('default_pagination')->default(10);
            $table->boolean('hide_done_tasks')->default(false);
            $table->boolean('hide_inactive_alarms')->default(false);
        });
    }
}
