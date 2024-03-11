<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserFacebook extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->bigInteger('facebook_id')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', static function(Blueprint $table) {
            $table->dropColumn('facebook_id');
        });
    }
}
