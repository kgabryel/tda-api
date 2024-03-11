<?php

use App\Shared\Domain\Config;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationsGroupIntervalInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('notifications_groups', static function(Blueprint $table) {
            $table->string('hour', 5);
            $table->integer('interval')->nullable(true);
            $table->string('interval_behaviour', Config::MAX_INTERVAL_NAME_LENGTH);
        });
    }

    public function down(): void
    {
        Schema::table('notifications_groups', static function(Blueprint $table) {
            $table->dropColumn('hour');
            $table->dropColumn('interval');
            $table->dropColumn('interval_behaviour');
        });
    }
}
