<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NotesCreated extends Migration
{
    public function up(): void
    {
        Schema::create('notes', static function(Blueprint $table) {
            $table->id();
            $table->string('title', Config::NOTE_NAME_LENGTH);
            $table->text('content');
            $table->string('background_color', 9);
            $table->string('text_color', 9);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->boolean('assigned_to_dashboard')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
}
