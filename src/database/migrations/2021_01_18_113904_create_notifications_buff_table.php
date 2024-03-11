<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotificationsBuffCreated extends Migration
{
    public function up(): void
    {
        Schema::create('notifications_buff', static function(Blueprint $table) {
            $table->id();
            $table->string('title', Config::ALARM_NAME_LENGTH);
            $table->text('content')->nullable();
            $table->dateTime('time');
            $table->boolean('locked')->default(false);
            $table->unsignedBigInteger('notification_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('notification_id')
                ->references('id')
                ->on('notifications')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_buff');
    }
}
