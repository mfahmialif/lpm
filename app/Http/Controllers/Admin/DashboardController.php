<?php
namespace App\Http\Controllers\Admin;

use App\Models\News;
use App\Models\User;
use App\Models\Activity;
use App\Models\Category;
use App\Models\Accreditation;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user          = \Auth::user();
        $totalCategory = Category::count();
        $totalNews     = News::count();
        $totalUser     = User::count();

        $countActivity = Activity::count();
        $countNews     = News::count();
        $countUser     = User::count();
        $countAccreditation = Accreditation::count();

        return view('admin/dashboard/index', compact('user', 'totalCategory', 'totalNews', 'totalUser', 'countActivity', 'countNews', 'countUser', 'countAccreditation'));
    }
}
