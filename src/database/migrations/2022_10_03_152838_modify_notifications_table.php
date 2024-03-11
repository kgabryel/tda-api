<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNotificationsTable extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', static function(Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->foreign('group_id')
                ->references('id')
                ->on('notifications_groups')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', static function(Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->foreign('group_id')
                ->references('id')
                ->on('notifications_groups')
                ->onDelete('cascade');
        });
    }
}
