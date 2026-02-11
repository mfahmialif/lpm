$rtm = App\Models\AmiRtm::find(1);
if ($rtm) {
    $skIds = $rtm->skAuditors()->allRelatedIds();
    echo "SK IDs: " . $skIds->toJson() . "\n";
    
    if ($skIds->isNotEmpty()) {
        $temuans = App\Models\AmiTemuanAudit::whereIn('ami_sk_auditor_id', $skIds)->get();
        echo "Temuans to sync: " . $temuans->count() . "\n";

        foreach ($temuans as $temuan) {
            App\Models\AmiRtmTemuan::updateOrCreate(
                ['ami_rtm_id' => $rtm->id, 'ami_temuan_audit_id' => $temuan->id],
                ['ami_sk_auditor_id' => $temuan->ami_sk_auditor_id]
            );
            
             $rtmTemuan = App\Models\AmiRtmTemuan::where('ami_rtm_id', $rtm->id)
                ->where('ami_temuan_audit_id', $temuan->id)
                ->first();
            if ($rtmTemuan && !$rtmTemuan->status_tindak_lanjut) {
                $rtmTemuan->update(['status_tindak_lanjut' => 'open']);
            }
        }
    }
    
    echo "Total RTM Temuan: " . App\Models\AmiRtmTemuan::where('ami_rtm_id', 1)->count() . "\n";
} else {
    echo "RTM 1 not found\n";
}
