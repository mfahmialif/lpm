<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Activity;
use Illuminate\Http\Request;

class RootController extends Controller
{
    public function index()
    {
        $news = News::where('status', 'published')->orderBy('id', 'desc')->limit(3)->get();
        $activity = Activity::where('status', 'published')->orderBy('id', 'desc')->limit(8)->get();
        return view('root.index', compact('news', 'activity'));
    }
}
