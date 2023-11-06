<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatalogsVideosTable extends Migration
{
    public function up(): void
    {
        Schema::create('catalogs_videos', static function(Blueprint $table) {
            $table->unsignedBigInteger('catalog_id');
            $table->foreign('catalog_id')
                ->references('id')
                ->on('catalogs')
                ->onDelete('cascade');
            $table->unsignedBigInteger('video_id');
            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade');
            $table->primary(['catalog_id', 'video_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catalogs_videos');
    }
}
