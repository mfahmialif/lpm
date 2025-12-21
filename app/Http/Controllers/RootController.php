<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Services\Helper;

class RootController extends Controller
{
    public function index()
    {
        $news = News::where('status', 'published')->orderBy('id', 'desc')->limit(3)->get();
        $activity = Activity::where('status', 'published')->orderBy('id', 'desc')->limit(8)->get();
        return view('root.index', compact('news', 'activity'));
    }

    public function setAmiModeUser(Request $request)
    {
        $request->validate([
            'mode' => 'required',
        ]);

        $set = Helper::setAmiMode($request->mode);

        if ($set) {
            return redirect()->back()->with('success', 'Mode berhasil diset: ' . ucfirst($request->mode));
        } else {
            return redirect()->back()->with('error', 'Mode tidak valid: ' . ucfirst($request->mode));
        }
    }
}
