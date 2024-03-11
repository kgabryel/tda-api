<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AvailableNotificationsTypesCreated extends Migration
{
    public function up(): void
    {
        Schema::create('available_notifications_types', static function(Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('color', 9);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('available_notifications_types');
    }
}
