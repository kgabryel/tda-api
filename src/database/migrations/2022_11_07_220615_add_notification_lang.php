<?php

use App\User\Domain\Entity\AvailableLanguage;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationLang extends Migration
{
    public function up(): void
    {
        Schema::table('settings', static function(Blueprint $table) {
            $table->enum('notification_lang', AvailableLanguage::getValues())
                ->default(AvailableLanguage::getDefault()->value);
        });
    }

    public function down(): void
    {
        Schema::table('settings', static function(Blueprint $table) {
            $table->dropColumn('notification_lang');
        });
    }
}
