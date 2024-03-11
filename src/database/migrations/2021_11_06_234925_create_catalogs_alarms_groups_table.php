<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsAlarmsGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::create('catalogs_alarms_groups', static function(Blueprint $table) {
            $table->unsignedBigInteger('catalog_id');
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
            $table->uuid('alarm_id');
            $table->foreign('alarm_id')
                ->references('id')
                ->on('alarms_groups')
                ->onDelete('cascade');
            $table->primary(['catalog_id', 'alarm_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogs_alarms_groups');
    }
}
