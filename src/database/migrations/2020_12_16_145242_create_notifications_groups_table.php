<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsGroupsCreated extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_groups', static function(Blueprint $table) {
            $table->id();
            $table->bigInteger('time');
            $table->uuid('alarm_id');
            $table->foreign('alarm_id')
                ->references('id')
                ->on('alarms_groups')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_groups');
    }
}
