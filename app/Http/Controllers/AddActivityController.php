<?php
namespace App\Http\Controllers;

use App\Http\Services\BulkData;
use App\Http\Services\Summernote;
use App\Models\Activity;
use App\Models\ActivityDocument;
use Illuminate\Http\Request;

class AddActivityController extends Controller
{
    public function index($code)
    {

        $activity = Activity::where('code', $code)->first();
        return view('addactivity.index', compact('activity', 'code'));
    }

    public function storeDokumen(Request $request, $code)
    {
        // ✅ Validasi dulu sebelum apa pun
        $request->validate([
            'tipe' => 'required|in:undangan,absensi,notulen,gambar',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,gif,webp',
        ], [
            'file.max'   => 'Ukuran file maksimal 10 MB.',
            'file.mimes' => 'File harus berupa PDF, Word, Excel, atau gambar.',
        ]);

        try {
            $activity = Activity::where('code', $code)->first();
            $folder   = public_path('activities/' . $activity->title);
            if (! file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            $file     = $request->file('file');
            $fileName = $request->tipe . '_' . uniqid() . '_' . $file->getClientOriginalName();

            $file->move($folder, $fileName);

            ActivityDocument::create([
                'activity_id' => $activity->id,
                'type'        => $request->tipe,
                'file'        => $fileName,
                'path'        => 'activities/' . $activity->title . '/' . $fileName,
            ]);
            return response()->json(['filename' => $fileName]);
        } catch (\Throwable $th) {
            if (file_exists($folder . '/' . $fileName)) {
                unlink($folder . '/' . $fileName);
            }
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function destroyDokumen(Request $request, $code)
    {
        $activity = Activity::where('code', $code)->first();
        $folder   = public_path('activities/' . $activity->title);
        $fileName = $request->filename;
        $path     = $folder . '/' . $fileName;

        $pathDeleteDb = 'activities/' . $activity->title . '/' . $fileName;
        ActivityDocument::where('path', $pathDeleteDb)->delete();

        if (file_exists($path)) {
            unlink($path);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'File tidak ditemukan', 'req' => $request->all()], 404);
    }

    public function getDataDokumen($code, Request $request)
    {
        try {
            $activity = Activity::where('code', $code)->first();
            $document = $activity->document->where('type', $request->type)->values()->toArray();
            return response()->json(['success' => true, 'data' => $document]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    public function store($code, Request $request)
    {
        $activity = Activity::where('code', $code)->first();
        if ($request->body) {
            $generate = Summernote::generateForEdit($activity->body, $request->body, BulkData::dirSummernote);
            foreach ($generate['paths'] as $keyPath => $path) {
                $paths[] = $path;
            }
            $request->body = $generate['data'];
        }

        $activity->body = $request->body;
        $activity->save();

        return back()->with('success', 'Data berhasil disimpan');

    }

}
