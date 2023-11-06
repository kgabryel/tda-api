<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BookmarksCreated extends Migration
{
    public function up(): void
    {
        Schema::create('bookmarks', static function(Blueprint $table) {
            $table->id();
            $table->string('title', Config::BOOKMARK_NAME_LENGTH);
            $table->text('href');
            $table->string('background_color', 9);
            $table->string('text_color', 9);
            $table->text('icon')->nullable();
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
        Schema::dropIfExists('bookmarks');
    }
}
