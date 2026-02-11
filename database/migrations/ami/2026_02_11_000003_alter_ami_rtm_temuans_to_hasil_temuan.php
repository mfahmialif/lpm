<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Get current foreign keys
        $fks = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ami_rtm_temuans' AND CONSTRAINT_TYPE = 'FOREIGN KEY'"))
            ->pluck('CONSTRAINT_NAME')->toArray();

        $ops = [];

        // 1. Drop FK on ami_temuan_audit_id if exists
        if (in_array('ami_rtm_temuans_ami_temuan_audit_id_foreign', $fks)) {
            $ops[] = 'DROP FOREIGN KEY ami_rtm_temuans_ami_temuan_audit_id_foreign';
        }

        // 2. Drop column (this will auto-drop dependent indexes including rtm_temuan_unique)
        if (Schema::hasColumn('ami_rtm_temuans', 'ami_temuan_audit_id')) {
            $ops[] = 'DROP COLUMN ami_temuan_audit_id';
        }

        if (count($ops) > 0) {
            DB::statement('ALTER TABLE ami_rtm_temuans ' . implode(', ', $ops));
        }

        // 3. Add FK column if missing
        if (!Schema::hasColumn('ami_rtm_temuans', 'ami_hasil_temuan_id')) {
            DB::statement('ALTER TABLE ami_rtm_temuans ADD COLUMN ami_hasil_temuan_id BIGINT UNSIGNED NOT NULL AFTER ami_sk_auditor_id');
        }

        // 4. Add FK constraint if missing
        $fks2 = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ami_rtm_temuans' AND CONSTRAINT_TYPE = 'FOREIGN KEY'"))
            ->pluck('CONSTRAINT_NAME')->toArray();

        if (!in_array('ami_rtm_temuans_ami_hasil_temuan_id_foreign', $fks2)) {
            DB::statement('ALTER TABLE ami_rtm_temuans ADD CONSTRAINT ami_rtm_temuans_ami_hasil_temuan_id_foreign FOREIGN KEY (ami_hasil_temuan_id) REFERENCES ami_hasil_temuans(id) ON DELETE CASCADE');
        }

        // 5. Add unique constraint if missing
        $newIdx = DB::select("SHOW INDEX FROM ami_rtm_temuans WHERE Key_name = 'rtm_hasil_temuan_unique'");
        if (count($newIdx) === 0) {
            DB::statement('ALTER TABLE ami_rtm_temuans ADD UNIQUE INDEX rtm_hasil_temuan_unique (ami_rtm_id, ami_hasil_temuan_id)');
        }
    }

    public function down()
    {
        $fks = collect(DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'ami_rtm_temuans' AND CONSTRAINT_TYPE = 'FOREIGN KEY'"))
            ->pluck('CONSTRAINT_NAME')->toArray();

        $ops = [];

        if (in_array('ami_rtm_temuans_ami_hasil_temuan_id_foreign', $fks)) {
            $ops[] = 'DROP FOREIGN KEY ami_rtm_temuans_ami_hasil_temuan_id_foreign';
        }

        if (Schema::hasColumn('ami_rtm_temuans', 'ami_hasil_temuan_id')) {
            $ops[] = 'DROP COLUMN ami_hasil_temuan_id';
        }

        if (count($ops) > 0) {
            DB::statement('ALTER TABLE ami_rtm_temuans ' . implode(', ', $ops));
        }

        if (!Schema::hasColumn('ami_rtm_temuans', 'ami_temuan_audit_id')) {
            DB::statement('ALTER TABLE ami_rtm_temuans ADD COLUMN ami_temuan_audit_id BIGINT UNSIGNED NOT NULL AFTER ami_sk_auditor_id');
            DB::statement('ALTER TABLE ami_rtm_temuans ADD CONSTRAINT ami_rtm_temuans_ami_temuan_audit_id_foreign FOREIGN KEY (ami_temuan_audit_id) REFERENCES ami_temuan_audits(id) ON DELETE CASCADE');
            DB::statement('ALTER TABLE ami_rtm_temuans ADD UNIQUE INDEX rtm_temuan_unique (ami_rtm_id, ami_temuan_audit_id)');
        }
    }
};
