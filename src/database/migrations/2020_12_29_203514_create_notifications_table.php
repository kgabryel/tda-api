<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsTypes extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_types', static function(Blueprint $table) {
            $table->integer('notification_id')->unsigned();
            $table->integer('type_id')->unsigned();
            $table->unique(['notification_id', 'type_id']);
            $table->foreign('notification_id')
                ->references('id')
                ->on('notifications')
                ->onDelete('cascade');
            $table->foreign('type_id')
                ->references('id')
                ->on('available_notifications_types')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_types');
    }
}
