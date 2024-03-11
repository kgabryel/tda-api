<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileCreated extends Migration
{
    public function up(): void
    {
        Schema::create('files', static function(Blueprint $table) {
            $table->id();
            $table->string('name', Config::FILE_NAME_LENGTH);
            $table->string('path', 36);
            $table->bigInteger('size', false, true);
            $table->string('parsed_size', 20);
            $table->string('extension', 260);
            $table->text('original_name');
            $table->text('mime_type');
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
        Schema::dropIfExists('files');
    }
}
