<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class DeleteGroupDeactivation extends Migration
{
    public function up(): void
    {
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->dropColumn('alarm_group_deactivation_code');
        });
        Schema::table('alarms_groups', static function(Blueprint $table) {
            $table->dropColumn('deactivation_code');
        });
    }

    public function down(): void
    {
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->uuid('alarm_group_deactivation_code')->default(Uuid::uuid4()->toString());
        });
        Schema::table('alarms_groups', static function(Blueprint $table) {
            $table->uuid('deactivation_code')->default(Uuid::uuid4()->toString());
        });
    }
}
