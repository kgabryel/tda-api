<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNotificationsBuffTable2 extends Migration
{
    public function up(): void
    {
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('user_id');
            $table->dropColumn('content');
            $table->dropColumn('alarm_deactivation_code');
        });
    }

    public function down(): void
    {
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->string('name', Config::ALARM_NAME_LENGTH);
            $table->text('content')->nullable();
            $table->uuid('alarm_deactivation_code')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
}
