<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable2 extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->unsignedInteger('default_pagination')->default(10);
            $table->boolean('hide_done_tasks')->default(false);
            $table->boolean('hide_inactive_alarms')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->dropColumn('default_pagination');
            $table->dropColumn('hide_done_tasks');
            $table->dropColumn('hide_inactive_alarms');
        });
    }
}
