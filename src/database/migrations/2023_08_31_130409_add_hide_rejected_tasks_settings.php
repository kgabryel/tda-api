<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHideRejectedTasksSettings extends Migration
{
    public function up(): void
    {
        Schema::table('settings', static function(Blueprint $table) {
            $table->boolean('hide_rejected_tasks')->default(true);
        });
    }

    public function down(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->dropColumn('hide_rejected_tasks');
        });
    }
}
