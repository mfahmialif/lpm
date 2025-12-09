<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Requirement;
use Illuminate\Http\Request;
use App\Models\Accreditation;
use Illuminate\Support\Facades\Cookie;

class AccreditationController extends Controller
{
    public function index(Request $request)
    {
        $accreditation = Accreditation::query();

        if ($request->search) {
            $accreditation->where('name', 'like', '%' . $request->search . '%');
        }

        $accreditation = $accreditation->paginate(10); // Paginate 10 items per page
        return view('accreditation.index', compact('accreditation'));
    }

    public function detail(Request $request, Accreditation $accreditation)
    {
        $requirements = Requirement::where('accreditation_id', $accreditation->id)->whereNull('parent_id')->get();
        return view('accreditation.detail.index', compact('accreditation', 'requirements'));
    }

}
