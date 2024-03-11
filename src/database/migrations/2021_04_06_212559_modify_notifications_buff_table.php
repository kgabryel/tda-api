<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNotificationsBuffTable extends Migration
{
    public function up(): void
    {
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->uuid('alarm_deactivation_code')->nullable();
            $table->uuid('alarm_group_deactivation_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->dropColumn('alarm_deactivation_code');
            $table->dropColumn('alarm_group_deactivation_code');
        });
    }
}
