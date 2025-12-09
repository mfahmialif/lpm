<?php
namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $activity = Activity::query();

        $activity->where('status', 'published');
        if ($request->unit) {
            $activity->whereHas('unit', function ($query) use ($request) {
                $query->where('name', $request->unit);
            });
        }

        if ($request->search) {
            $activity->where('title', 'like', '%' . $request->search . '%');
        }
        
        $activity->orderBy('id', 'desc');

        $activity = $activity->with('unit', 'tag')->paginate(5); // Paginate 10 items per page

        $author = User::find(1);
        $unit   = Unit::all();

        $recentActivity = Activity::where('status', 'published')->orderBy('id', 'desc')->limit(3)->get();
        return view('activity.index', compact('activity', 'author', 'unit', 'request', 'recentActivity'));
    }

    public function detail(Request $request, $slug)
    {
        $activity = Activity::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $author   = User::find(1);
        $unit     = Unit::all();

        $recentActivity = Activity::orderBy('id', 'desc')->limit(3)->get();

        $rememberUser = Cookie::get('remember_user_comment');

        $unit = Unit::all();

        $recentActivity = Activity::where('status', 'published')->orderBy('id', 'desc')->limit(3)->get();
    return view('activity.detail.index', compact('activity', 'author', 'request', 'recentActivity', 'recentActivity', 'unit'));
    }

}
