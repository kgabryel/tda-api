<?php

use App\Alarm\Infrastructure\Model\AlarmGroup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ramsey\Uuid\Uuid;

class ModifyAlarmsGroupsTable extends Migration
{
    public function up(): void
    {
        Schema::table('alarms_groups', static function(Blueprint $table) {
            $table->uuid('deactivation_code')->nullable();
        });
        foreach (AlarmGroup::all() as $alarm) {
            $alarm->deactivation_code = Uuid::uuid4()->toString();
            $alarm->save();
        }
        Schema::table('alarms_groups', static function(Blueprint $table) {
            $table->uuid('deactivation_code')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('alarms_groups', static function(Blueprint $table) {
            $table->dropColumn('deactivation_code');
        });
    }
}
