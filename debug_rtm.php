<?php

use App\Models\AmiRtm;
use App\Models\AmiRtmTemuan;
use App\Models\AmiTemuanAudit;

$rtm = AmiRtm::find(1);
if (!$rtm) {
    echo "RTM not found\n";
    exit;
}

echo "RTM found: " . $rtm->kode_rtm . "\n";

// Check linked SKs
$skIds = $rtm->skAuditors()->pluck('ami_sk_auditors.id');
echo "Linked SK IDs: " . $skIds->toJson() . "\n";

if ($skIds->isEmpty()) {
    echo "No SK linked to this RTM.\n";
    exit;
}

// Check temuans for these SKs
$temuans = AmiTemuanAudit::whereIn('ami_sk_auditor_id', $skIds)->get();
echo "Temuans found: " . $temuans->count() . "\n";

foreach ($temuans as $t) {
    echo " - Temuan ID: {$t->id} (SK ID: {$t->ami_sk_auditor_id})\n";
}

// Try to create the RTM Temuan
foreach ($temuans as $temuan) {
    echo "Creating AmiRtmTemuan for Temuan ID {$temuan->id}...\n";
    try {
        $rtmTemuan = AmiRtmTemuan::create([
            'ami_rtm_id' => $rtm->id,
            'ami_sk_auditor_id' => $temuan->ami_sk_auditor_id,
            'ami_temuan_audit_id' => $temuan->id,
            'status_tindak_lanjut' => 'open',
        ]);
        echo "Created RTM Temuan ID: {$rtmTemuan->id}\n";
    } catch (\Exception $e) {
        echo "Error creating: " . $e->getMessage() . "\n";
    }
}
