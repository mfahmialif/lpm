<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SambutanKetua;

class SambutanKetuaController extends Controller
{
    public function index()
    {
        $sambutan = SambutanKetua::first();
        return view('sambutan_ketua.index', compact('sambutan'));
    }
}
