<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsCreated extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', static function(Blueprint $table) {
            $table->id();
            $table->dateTime('time');
            $table->boolean('checked');
            $table->uuid('alarm_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('alarm_id')
                ->references('id')
                ->on('alarms')
                ->onDelete('cascade');
            $table->foreign('group_id')
                ->references('id')
                ->on('notifications_groups')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}
