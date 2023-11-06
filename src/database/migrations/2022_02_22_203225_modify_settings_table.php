<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifySettingsTable extends Migration
{
    public function up(): void
    {
        Schema::table('settings', static function(Blueprint $table) {
            $table->boolean('autocomplete')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->dropColumn('autocomplete');
            $table->dropColumn('hide_done_tasks');
            $table->dropColumn('hide_inactive_alarms');
        });
    }
}
