<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColorsTable extends Migration
{
    public function up(): void
    {
        Schema::create('colors', static function(Blueprint $table) {
            $table->id();
            $table->string('color', 9);
            $table->string('name', Config::COLOR_NAME_LENGTH);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->unique(['color', 'user_id']);
            $table->unique(['name', 'user_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colors');
    }
}
