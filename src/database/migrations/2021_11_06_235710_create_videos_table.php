<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up(): void
    {
        Schema::create('videos', static function(Blueprint $table) {
            $table->id();
            $table->string('name', Config::VIDEO_NAME_LENGTH);
            $table->string('yt_id', 20);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
}
