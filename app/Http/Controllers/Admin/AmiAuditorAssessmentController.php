<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiPeriod;
use App\Models\ProdiUnit;
use Illuminate\Http\Request;
use App\Http\Services\Helper;
use Yajra\DataTables\DataTables;
use App\Models\AmiAssessmentScore;
use App\Http\Controllers\Controller;
use App\Models\AmiAuditorAssessment;
use Illuminate\Support\Facades\File;
use App\Models\AmiAssessmentResponse;
use App\Models\AmiAssessmentIndicator;

class AmiAuditorAssessmentController extends Controller
{
    private $uploadDir = 'storage/documents/ami-auditor-assessment/';

    public function index()
    {
        $prodiUnits = ProdiUnit::all();
        return view('admin.ami-auditor-assessment.index', compact('prodiUnits'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiAuditorAssessment::select(
            'ami_auditor_assessments.*',
            'ami_periods.year as ami_period',
            'prodi_units.nama as prodi_unit_name'
        )
            ->withCount(['responses', 'selectedScores'])
            ->leftJoin('ami_periods', 'ami_auditor_assessments.ami_period_id', '=', 'ami_periods.id')
            ->leftJoin('prodi_units', 'ami_auditor_assessments.prodi_unit_id', '=', 'prodi_units.id');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('ami_auditor_assessments.assessment_guide', 'LIKE', "%$search%");
                    $query->orWhere('ami_auditor_assessments.auditee_name', 'LIKE', "%$search%");
                    $query->orWhere('ami_auditor_assessments.note', 'LIKE', "%$search%");
                    $query->orWhere('ami_periods.year', 'LIKE', "%$search%");
                    $query->orWhere('prodi_units.nama', 'LIKE', "%$search%");
                });
                $query->when(request('prodi_unit_id') != '*', function ($query) {
                    $query->where('ami_auditor_assessments.prodi_unit_id', request('prodi_unit_id'));
                });
            })
            ->editColumn('ami_period', function ($row) {
                return $row->ami_period ?: '-';
            })
            ->editColumn('prodi_unit_name', function ($row) {
                return $row->prodi_unit_name ?: '-';
            })
            ->editColumn('status', function ($row) {
                return '<span class="badge bg-' . ($row->status == 'y' ? 'success' : 'secondary') . '">' . ($row->status == 'y' ? 'Active' : 'Inactive') . '</span>';
            })
            ->editColumn('document', function ($row) {
                if ($row->document) {
                    return '<a href="' . asset($this->uploadDir . $row->document) . '" target="_blank" class="btn btn-sm btn-info">View</a>';
                }
                return '-';
            })
            ->addColumn('respon', function ($row) {
                $count = $row->responses_count;
                if ($count > 0) {
                    return '<span class="badge bg-primary rounded-pill"><i class="ti ti-message-circle me-1"></i>' . $count . '</span>';
                }
                return '<span class="badge bg-light text-muted">0</span>';
            })
            ->addColumn('status_indikator', function ($row) {
                if ($row->selected_scores_count > 0) {
                    return '<span class="badge bg-success"><i class="ti ti-check me-1"></i>Sudah Diisi</span>';
                }
                return '<span class="badge bg-danger"><i class="ti ti-x me-1"></i>Belum Diisi</span>';
            })
            ->addColumn('action', function ($row) {
                $user = auth()->user();
                $isAdmin = $user->role === 'admin';

                $actionButtons = '
                    <div class="d-inline-block">
                        <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical ti-md"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end m-0">
                            <li>
                                <a class="dropdown-item" href="' . route('admin.ami-auditor-assessment.response', ['amiAuditorAssessment' => $row->id]) . '"><i class="ti ti-message-circle me-1"></i>Respon</a>
                            </li>';

                if ($isAdmin) {
                    $actionButtons .= '<li>
                                <a class="dropdown-item" href="' . route('admin.ami-auditor-assessment.indikator', ['amiAuditorAssessment' => $row->id]) . '"><i class="ti ti-chart-bar me-1"></i>Indikator</a>
                            </li>';
                }

                $actionButtons .= '<li>
                                <a class="dropdown-item" href="' . route('admin.ami-auditor-assessment.isiIndikator', ['amiAuditorAssessment' => $row->id]) . '"><i class="ti ti-checkbox me-1"></i>Isi Skor Indikator</a>
                            </li>';

                if ($isAdmin) {
                    $actionButtons .= '<li>
                                <a class="dropdown-item" href="' . route('admin.ami-auditor-assessment.edit', ['amiAuditorAssessment' => $row->id]) . '">Edit</a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Asesmen Auditor ' . ($row->prodi_name ?: '') . '">
                                    <button type="submit" class="dropdown-item text-danger">Delete</button>
                                </form>
                            </li>';
                }

                $actionButtons .= '</ul>
                    </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'status', 'document', 'respon', 'status_indikator'])
            ->toJson();
    }

    public function add()
    {
        $periods = AmiPeriod::all();
        $prodiUnits = ProdiUnit::all();
        $auditees = \App\Models\User::whereHas('prodiUnits', function ($query) {
            $query->where('user_prodi_unit.jenis', 'auditee');
        })->get();
        return view('admin.ami-auditor-assessment.add.index', compact('periods', 'prodiUnits', 'auditees'));
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_unit_id' => 'required',
                'assessment_guide' => 'nullable',
                'auditee_name' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
                'status' => 'nullable',
            ]);

            $assessment = new AmiAuditorAssessment();
            $assessment->ami_period_id = $request->ami_period_id;
            $assessment->prodi_unit_id = $request->prodi_unit_id;
            $assessment->assessment_guide = $request->assessment_guide;
            $assessment->auditee_name = $request->auditee_name;
            $assessment->note = $request->note;
            $assessment->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $assessment->document = $fileName;
            }

            $assessment->save();

            \DB::commit();
            return redirect()->route('admin.ami-auditor-assessment.index')->with('success', 'Berhasil menambahkan Asesmen Auditor AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-auditor-assessment.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-auditor-assessment.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiAuditorAssessment $amiAuditorAssessment)
    {
        $periods = AmiPeriod::all();
        $prodiUnits = ProdiUnit::all();
        $auditees = \App\Models\User::whereHas('prodiUnits', function ($query) {
            $query->where('user_prodi_unit.jenis', 'auditee');
        })->get();
        return view('admin.ami-auditor-assessment.edit.index', compact('periods', 'prodiUnits', 'amiAuditorAssessment', 'auditees'));
    }

    public function update(AmiAuditorAssessment $amiAuditorAssessment, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_unit_id' => 'required',
                'assessment_guide' => 'nullable',
                'auditee_name' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'note' => 'nullable',
                'status' => 'nullable',
            ]);

            $amiAuditorAssessment->ami_period_id = $request->ami_period_id;
            $amiAuditorAssessment->prodi_unit_id = $request->prodi_unit_id;
            $amiAuditorAssessment->assessment_guide = $request->assessment_guide;
            $amiAuditorAssessment->auditee_name = $request->auditee_name;
            $amiAuditorAssessment->note = $request->note;
            $amiAuditorAssessment->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                if ($amiAuditorAssessment->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiAuditorAssessment->document);
                    if (File::exists($oldDocPath)) {
                        File::delete($oldDocPath);
                    }
                }

                $file = $request->file('document');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->uploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $amiAuditorAssessment->document = $fileName;
            }

            $amiAuditorAssessment->save();

            \DB::commit();
            return redirect()->route('admin.ami-auditor-assessment.index')->with('success', 'Berhasil mengupdate Asesmen Auditor AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-auditor-assessment.edit', ['amiAuditorAssessment' => $amiAuditorAssessment->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-auditor-assessment.edit', ['amiAuditorAssessment' => $amiAuditorAssessment->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate(['id' => 'required']);

            $data = AmiAuditorAssessment::findOrFail($request->id);

            if ($data->document) {
                $docPath = public_path($this->uploadDir . $data->document);
                if (File::exists($docPath)) {
                    File::delete($docPath);
                }
            }

            $data->delete();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success',
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }

    private $responseUploadDir = 'storage/documents/ami-assessment-responses/';

    /**
     * Display the response/chat page for an assessment.
     */
    public function response(AmiAuditorAssessment $amiAuditorAssessment)
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $isUnit = $user->role === 'unit';
        $amiMode = strtolower(Helper::getAmiMode());
        $canInputResponse = $isAdmin || ($isUnit && $amiMode === 'auditee');

        $amiAuditorAssessment->load(['amiPeriod', 'prodiUnit']);
        return view('admin.ami-auditor-assessment.response.index', compact('amiAuditorAssessment', 'canInputResponse'));
    }

    /**
     * Store a new response message.
     */
    public function storeResponse(Request $request, AmiAuditorAssessment $amiAuditorAssessment)
    {
        try {
            \DB::beginTransaction();

            $request->validate([
                'message' => 'required_without:attachment|nullable|string',
                'attachment' => 'nullable|file|max:10240', // 10MB max
            ]);

            $response = new AmiAssessmentResponse();
            $response->ami_auditor_assessment_id = $amiAuditorAssessment->id;
            $response->user_id = auth()->id();
            $response->message = $request->message;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = time() . '-' . $file->getClientOriginalName();
                $destinationPath = public_path($this->responseUploadDir);
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $fileName);
                $response->attachment = $fileName;
                $response->attachment_name = $file->getClientOriginalName();
            }

            $response->save();

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Pesan berhasil dikirim',
                'data' => [
                    'id' => $response->id,
                    'message' => $response->message,
                    'attachment' => $response->attachment ? asset($this->responseUploadDir . $response->attachment) : null,
                    'attachment_name' => $response->attachment_name,
                    'user' => [
                        'id' => auth()->id(),
                        'name' => auth()->user()->name,
                        'role' => auth()->user()->role,
                    ],
                    'created_at' => $response->created_at->format('d M Y H:i'),
                    'is_own' => true,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => implode('<br>', array_map('implode', $e->errors())),
            ], 422);
        } catch (\Throwable $th) {
            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all responses for an assessment.
     */
    public function getResponses(AmiAuditorAssessment $amiAuditorAssessment)
    {
        $responses = $amiAuditorAssessment->responses()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($response) {
                return [
                    'id' => $response->id,
                    'message' => $response->message,
                    'attachment' => $response->attachment ? asset($this->responseUploadDir . $response->attachment) : null,
                    'attachment_name' => $response->attachment_name,
                    'user' => [
                        'id' => $response->user_id,
                        'name' => $response->user->name ?? 'Unknown',
                        'role' => $response->user->role ?? 'user',
                    ],
                    'created_at' => $response->created_at->format('d M Y H:i'),
                    'is_own' => $response->user_id === auth()->id(),
                ];
            });

        return response()->json([
            'status' => true,
            'data' => $responses,
        ]);
    }

    /**
     * Update a response message.
     */
    public function updateResponse(Request $request, $responseId)
    {
        try {
            \DB::beginTransaction();

            $response = AmiAssessmentResponse::findOrFail($responseId);

            // Check permission: admin can edit all, others only their own
            if (auth()->user()->role !== 'admin' && $response->user_id !== auth()->id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki izin untuk mengedit pesan ini',
                ], 403);
            }

            $request->validate([
                'message' => 'required|string',
            ]);

            $response->message = $request->message;
            $response->save();

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Pesan berhasil diperbarui',
                'data' => [
                    'id' => $response->id,
                    'message' => $response->message,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => implode('<br>', array_map('implode', $e->errors())),
            ], 422);
        } catch (\Throwable $th) {
            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a response message.
     */
    public function deleteResponse($responseId)
    {
        try {
            \DB::beginTransaction();

            $response = AmiAssessmentResponse::findOrFail($responseId);

            // Check permission: admin can delete all, others only their own
            if (auth()->user()->role !== 'admin' && $response->user_id !== auth()->id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus pesan ini',
                ], 403);
            }

            // Delete attachment file if exists
            if ($response->attachment) {
                $filePath = public_path($this->responseUploadDir . $response->attachment);
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            $response->delete();

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Pesan berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the indicator page for an assessment.
     */
    public function indikator(AmiAuditorAssessment $amiAuditorAssessment)
    {
        $indicator = $amiAuditorAssessment->indicator;
        $scores = $indicator ? $indicator->scores : collect([]);
        return view('admin.ami-auditor-assessment.indikator.index', compact('amiAuditorAssessment', 'indicator', 'scores'));
    }

    /**
     * Store or update the indicator.
     */
    public function storeIndicator(Request $request, AmiAuditorAssessment $amiAuditorAssessment)
    {
        try {
            $request->validate([
                'indicator' => 'required|string',
            ]);

            $indicator = AmiAssessmentIndicator::updateOrCreate(
                ['ami_auditor_assessment_id' => $amiAuditorAssessment->id],
                ['indicator' => $request->indicator]
            );

            return response()->json([
                'status' => true,
                'message' => 'Indikator berhasil disimpan',
                'data' => [
                    'id' => $indicator->id,
                    'indicator' => $indicator->indicator,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a new score.
     */
    public function storeScore(Request $request, $indicatorId)
    {
        try {
            $request->validate([
                'score' => 'required|integer',
                'description' => 'required|string',
            ]);

            $indicator = AmiAssessmentIndicator::findOrFail($indicatorId);

            $score = AmiAssessmentScore::create([
                'ami_assessment_indicator_id' => $indicator->id,
                'score' => $request->score,
                'description' => $request->description,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Penskoran berhasil ditambahkan',
                'data' => [
                    'id' => $score->id,
                    'score' => $score->score,
                    'description' => $score->description,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a score.
     */
    public function deleteScore($scoreId)
    {
        try {
            $score = AmiAssessmentScore::findOrFail($scoreId);
            $score->delete();

            return response()->json([
                'status' => true,
                'message' => 'Penskoran berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a score.
     */
    public function updateScore(Request $request, $scoreId)
    {
        try {
            $request->validate([
                'score' => 'required|integer',
                'description' => 'required|string',
            ]);

            $score = AmiAssessmentScore::findOrFail($scoreId);
            $score->score = $request->score;
            $score->description = $request->description;
            $score->save();

            return response()->json([
                'status' => true,
                'message' => 'Penskoran berhasil diperbarui',
                'data' => [
                    'id' => $score->id,
                    'score' => $score->score,
                    'description' => $score->description,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the isi indikator page.
     */
    public function isiIndikator(AmiAuditorAssessment $amiAuditorAssessment)
    {
        $user = auth()->user();
        $isAdmin = $user->role === 'admin';
        $isUnit = $user->role === 'unit';
        $amiMode = strtolower(Helper::getAmiMode());
        $canInputScore = $isAdmin || ($isUnit && $amiMode === 'auditor');

        $indicator = $amiAuditorAssessment->indicator;
        $scores = $indicator ? $indicator->scores : collect([]);
        $selectedScoreIds = $amiAuditorAssessment->selectedScores->pluck('id')->toArray();
        
        return view('admin.ami-auditor-assessment.isi-indikator.index', compact('amiAuditorAssessment', 'indicator', 'scores', 'selectedScoreIds', 'canInputScore'));
    }

    /**
     * Store selected scores for indicator.
     */
    public function storeIsiIndikator(Request $request, AmiAuditorAssessment $amiAuditorAssessment)
    {
        try {
            $request->validate([
                'score_ids' => 'present|array',
                'score_ids.*' => 'exists:ami_assessment_scores,id',
            ]);

            $amiAuditorAssessment->selectedScores()->sync($request->score_ids);

            return response()->json([
                'status' => true,
                'message' => 'Isian indikator berhasil disimpan',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}




