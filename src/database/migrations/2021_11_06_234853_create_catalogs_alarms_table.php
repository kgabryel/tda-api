<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsAlarmsTable extends Migration
{
    public function up(): void
    {
        Schema::create('catalogs_alarms', static function(Blueprint $table) {
            $table->unsignedBigInteger('catalog_id');
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
            $table->uuid('alarm_id');
            $table->foreign('alarm_id')
                ->references('id')
                ->on('alarms')
                ->onDelete('cascade');
            $table->primary(['catalog_id', 'alarm_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogs_alarms');
    }
}
