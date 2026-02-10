<?php

namespace App\Http\Controllers;

use App\Models\AkreditasiKampus;
use Illuminate\Http\Request;
use App\Models\SkorAkreditasi;

class SkorAkreditasiController extends Controller
{
    public function index()
    {
        $skorAkreditasis = SkorAkreditasi::with('prodi')
            ->orderBy('status', 'asc') // masih berlaku first
            ->orderBy('tahun_sk', 'desc')
            ->get();

        $akreditasiKampus = \App\Models\AkreditasiKampus::all();
        $kampus = AkreditasiKampus::where('status', 'tidak')->count();

        return view('skor_akreditasi.index', compact('skorAkreditasis', 'akreditasiKampus', 'kampus'));
    }
}
