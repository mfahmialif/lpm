<?php

namespace App\Http\Controllers\Admin;

use App\Models\AmiSelfAssessment;
use App\Models\AmiPeriod;
use App\Models\ProdiUnit;
use App\Models\AmiSelfAssessmentIndicator;
use App\Models\AmiSelfAssessmentScore;
use App\Models\AmiSelfAssessmentResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AmiSelfAssessmentController extends Controller
{
    private $uploadDir = 'storage/documents/ami-self-assessment/';

    public function index()
    {
        $prodiUnits = ProdiUnit::all();
        return view('admin.ami-self-assessment.index', compact('prodiUnits'));
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = AmiSelfAssessment::select(
            'ami_self_assessments.*',
            'ami_periods.year as ami_period',
            'prodi_units.nama as prodi_unit_name'
        )
            ->withCount(['responses', 'selectedScores'])
            ->leftJoin('ami_periods', 'ami_self_assessments.ami_period_id', '=', 'ami_periods.id')
            ->leftJoin('prodi_units', 'ami_self_assessments.prodi_unit_id', '=', 'prodi_units.id');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('ami_self_assessments.assessment_guide', 'LIKE', "%$search%");
                    $query->orWhere('ami_self_assessments.auditee_name', 'LIKE', "%$search%");
                    $query->orWhere('ami_periods.year', 'LIKE', "%$search%");
                    $query->orWhere('prodi_units.nama', 'LIKE', "%$search%");
                });
                $query->when(request('prodi_unit_id') != '*', function ($query) {
                    $query->where('ami_self_assessments.prodi_unit_id', request('prodi_unit_id'));
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
                                <a class="dropdown-item" href="' . route('admin.ami-self-assessment.response', ['amiSelfAssessment' => $row->id]) . '"><i class="ti ti-message-circle me-1"></i>Respon</a>
                            </li>';

                if ($isAdmin) {
                    $actionButtons .= '<li>
                                <a class="dropdown-item" href="' . route('admin.ami-self-assessment.indikator', ['amiSelfAssessment' => $row->id]) . '"><i class="ti ti-chart-bar me-1"></i>Indikator</a>
                            </li>';
                }

                $actionButtons .= '<li>
                                <a class="dropdown-item" href="' . route('admin.ami-self-assessment.isiIndikator', ['amiSelfAssessment' => $row->id]) . '"><i class="ti ti-checkbox me-1"></i>Isi Skor Indikator</a>
                            </li>';

                if ($isAdmin) {
                    $actionButtons .= '<li>
                                <a class="dropdown-item" href="' . route('admin.ami-self-assessment.edit', ['amiSelfAssessment' => $row->id]) . '">Edit</a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <form class="form-delete-record">
                                ' . method_field('DELETE') . csrf_field() . '
                                    <input type="hidden" name="id" value="' . $row->id . '">
                                    <input type="hidden" name="nama" value="Asesmen Diri ' . ($row->prodi ? $row->prodi->nama : '') . '">
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
        return view('admin.ami-self-assessment.add.index', compact('periods', 'prodiUnits'));
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
                'status' => 'nullable',
            ]);

            $assessment = new AmiSelfAssessment();
            $assessment->ami_period_id = $request->ami_period_id;
            $assessment->prodi_unit_id = $request->prodi_unit_id;
            $assessment->assessment_guide = $request->assessment_guide;
            $assessment->auditee_name = $request->auditee_name;
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
            return redirect()->route('admin.ami-self-assessment.index')->with('success', 'Berhasil menambahkan Asesmen Diri AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-self-assessment.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-self-assessment.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(AmiSelfAssessment $amiSelfAssessment)
    {
        $periods = AmiPeriod::all();
        $prodiUnits = ProdiUnit::all();
        return view('admin.ami-self-assessment.edit.index', compact('periods', 'prodiUnits', 'amiSelfAssessment'));
    }

    public function update(AmiSelfAssessment $amiSelfAssessment, Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'ami_period_id' => 'required',
                'prodi_unit_id' => 'required',
                'assessment_guide' => 'nullable',
                'auditee_name' => 'nullable',
                'document' => 'nullable|mimes:pdf,doc,docx,xls,xlsx',
                'status' => 'nullable',
            ]);

            $amiSelfAssessment->ami_period_id = $request->ami_period_id;
            $amiSelfAssessment->prodi_unit_id = $request->prodi_unit_id;
            $amiSelfAssessment->assessment_guide = $request->assessment_guide;
            $amiSelfAssessment->auditee_name = $request->auditee_name;
            $amiSelfAssessment->status = $request->status ?? 'n';

            if ($request->hasFile('document')) {
                if ($amiSelfAssessment->document) {
                    $oldDocPath = public_path($this->uploadDir . $amiSelfAssessment->document);
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
                $amiSelfAssessment->document = $fileName;
            }

            $amiSelfAssessment->save();

            \DB::commit();
            return redirect()->route('admin.ami-self-assessment.index')->with('success', 'Berhasil mengupdate Asesmen Diri AMI');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->route('admin.ami-self-assessment.edit', ['amiSelfAssessment' => $amiSelfAssessment->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            return redirect()->route('admin.ami-self-assessment.edit', ['amiSelfAssessment' => $amiSelfAssessment->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate(['id' => 'required']);

            $data = AmiSelfAssessment::findOrFail($request->id);

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
    // Response Features
    public function response(AmiSelfAssessment $amiSelfAssessment)
    {
        $responses = $amiSelfAssessment->responses()->with('user')->orderBy('created_at', 'asc')->get();
        return view('admin.ami-self-assessment.response.index', compact('amiSelfAssessment', 'responses'));
    }

    public function storeResponse(Request $request, AmiSelfAssessment $amiSelfAssessment)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'message' => 'required_without:attachment|nullable|string',
                'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx|max:10240',
            ]);

            $response = new AmiSelfAssessmentResponse();
            $response->ami_self_assessment_id = $amiSelfAssessment->id;
            $response->user_id = auth()->id();
            $response->message = $request->message ?? '';

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $destinationPath = public_path('storage/documents/ami-self-assessment/responses/');
                $fileName = time() . '-' . $file->getClientOriginalName();
                
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $fileName);
                $response->attachment = $fileName;
                $response->attachment_name = $file->getClientOriginalName();
            }

            $response->save();
            $response->load('user');

            \DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Respon berhasil dikirim',
                'data' => [
                    'id' => $response->id,
                    'message' => $response->message,
                    'attachment' => $response->attachment ? asset('storage/documents/ami-self-assessment/responses/' . $response->attachment) : null,
                    'attachment_name' => $response->attachment_name,
                    'user' => [
                        'id' => $response->user_id,
                        'name' => $response->user->name ?? 'Unknown',
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
            \DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    
    public function getResponses(AmiSelfAssessment $amiSelfAssessment)
    {
        $responses = $amiSelfAssessment->responses()->with('user')->orderBy('created_at', 'asc')->get();
        $formattedResponses = $responses->map(function ($response) {
            return [
                'id' => $response->id,
                'message' => $response->message,
                'attachment' => $response->attachment ? asset('storage/documents/ami-self-assessment/responses/' . $response->attachment) : null,
                'attachment_name' => $response->attachment_name,
                'user' => [
                    'id' => $response->user_id,
                    'name' => $response->user->name ?? 'Unknown',
                    'role' => $response->user->role ?? '',
                ],
                'created_at' => $response->created_at->format('d M Y H:i'),
                'is_own' => $response->user_id === auth()->id(),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $formattedResponses,
        ]);
    }

    public function deleteResponse($responseId)
    {
        try {
            $response = AmiSelfAssessmentResponse::findOrFail($responseId);
            
            // Authorization check
            if (auth()->user()->role !== 'admin' && $response->user_id !== auth()->id()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
            }

            if ($response->attachment) {
                $path = public_path('storage/documents/ami-self-assessment/responses/' . $response->attachment);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            
            $response->delete();

            return response()->json([
                'status' => true,
                'message' => 'Pesan berhasil dihapus',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function updateResponse(Request $request, $responseId)
    {
        try {
            $request->validate([
                'message' => 'required|string',
            ]);

            $response = AmiSelfAssessmentResponse::findOrFail($responseId);

            // Authorization check
            if (auth()->user()->role !== 'admin' && $response->user_id !== auth()->id()) {
                return response()->json(['status' => false, 'message' => 'Unauthorized'], 403);
            }

            $response->update([
                'message' => $request->message,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Pesan berhasil diperbarui',
                'data' => [
                    'id' => $response->id,
                    'message' => $response->message,
                ],
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    // Indikator & Scoring Features
    public function indikator(AmiSelfAssessment $amiSelfAssessment)
    {
        $indicator = $amiSelfAssessment->indicator;
        $scores = collect([]);
        if ($indicator) {
            $scores = $indicator->scores()->orderBy('score', 'desc')->get();
        }
        return view('admin.ami-self-assessment.indikator.index', compact('amiSelfAssessment', 'indicator', 'scores'));
    }

    public function storeIndicator(Request $request, AmiSelfAssessment $amiSelfAssessment)
    {
        try {
            $request->validate([
                'indicator' => 'required|string',
            ]);

            $indicator = AmiSelfAssessmentIndicator::updateOrCreate(
                ['ami_self_assessment_id' => $amiSelfAssessment->id],
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

    public function storeScore(Request $request, $indicatorId)
    {
        try {
            $request->validate([
                'score' => 'required|integer',
                'description' => 'required|string',
            ]);

            $indicator = AmiSelfAssessmentIndicator::findOrFail($indicatorId);

            $score = AmiSelfAssessmentScore::create([
                'ami_self_assessment_indicator_id' => $indicator->id,
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

    public function deleteScore($scoreId)
    {
        try {
            $score = AmiSelfAssessmentScore::findOrFail($scoreId);
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

    public function updateScore(Request $request, $scoreId)
    {
        try {
            $request->validate([
                'score' => 'required|integer',
                'description' => 'required|string',
            ]);

            $score = AmiSelfAssessmentScore::findOrFail($scoreId);
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

    // Isi Indikator Features
    public function isiIndikator(AmiSelfAssessment $amiSelfAssessment)
    {
        $indicator = $amiSelfAssessment->indicator;
        $scores = $indicator ? $indicator->scores : collect([]);
        $selectedScoreIds = $amiSelfAssessment->selectedScores->pluck('id')->toArray();
        
        return view('admin.ami-self-assessment.isi-indikator.index', compact('amiSelfAssessment', 'indicator', 'scores', 'selectedScoreIds'));
    }

    public function storeIsiIndikator(Request $request, AmiSelfAssessment $amiSelfAssessment)
    {
        try {
            $request->validate([
                'score_ids' => 'present|array',
                'score_ids.*' => 'exists:ami_self_assessment_scores,id',
            ]);

            // Sync using the relationship provided in model
            $amiSelfAssessment->selectedScores()->sync($request->score_ids);

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
