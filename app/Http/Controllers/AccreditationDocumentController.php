<?php

namespace App\Http\Controllers;

use App\Models\DocumentAccreditation;
use Illuminate\Http\Request;

class AccreditationDocumentController extends Controller
{

    /**
     * Show the privacy policy page.
     *
     * @return \Illuminate\Contracts\View\View
     */

    public function index()
    {
        $data = DocumentAccreditation::join('prodis', 'document_accreditations.prodi_id', '=', 'prodis.id')
            ->select('document_accreditations.*', 'prodis.nama as prodi_name') // sesuaikan kolomnya
            ->paginate(1);
        $listGroup = DocumentAccreditation::join('prodis', 'document_accreditations.prodi_id', '=', 'prodis.id')->select('document_accreditations.*', 'prodis.nama as prodi_name')->get();
        return view('privacy-policy.index', compact('data', 'listGroup'));
    }
}
