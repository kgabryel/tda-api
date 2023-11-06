<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersTable extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->string('notification_email')->unique()->nullable();
            $table->uuid('activation_code')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->dropColumn('notification_email');
            $table->dropColumn('activation_code');
        });
    }
}
