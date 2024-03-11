<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTitlesToNames extends Migration
{
    public function up(): void
    {
        Schema::table('alarms', static function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
        Schema::table('alarms_groups', static function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
        Schema::table('bookmarks', static function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
        Schema::table('notes', static function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
        Schema::table('tasks', static function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
        Schema::table('tasks_groups', static function(Blueprint $table) {
            $table->renameColumn('title', 'name');
        });
    }

    public function down(): void
    {
        Schema::table('alarms', static function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
        Schema::table('alarms_groups', static function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
        Schema::table('bookmarks', static function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
        Schema::table('notes', static function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
        Schema::table('notifications_buff', static function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
        Schema::table('tasks', static function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
        Schema::table('tasks_groups', static function(Blueprint $table) {
            $table->renameColumn('name', 'title');
        });
    }
}
