<?php

use App\Alarm\Infrastructure\Model\Alarm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class ModifyAlarmsTable extends Migration
{
    public function up(): void
    {
        Schema::table('alarms', static function(Blueprint $table) {
            $table->uuid('deactivation_code')->nullable();
        });
        foreach (Alarm::all() as $alarm) {
            $alarm->deactivation_code = Uuid::uuid4()->toString();
            $alarm->save();
        }
        Schema::table('alarms', static function(Blueprint $table) {
            $table->uuid('deactivation_code')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('alarms', static function(Blueprint $table) {
            $table->dropColumn('deactivation_code');
        });
    }
}
