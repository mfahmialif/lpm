<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeLpm;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PeriodeLpmController extends Controller
{
    public function index()
    {
        return view('admin.periode_lpm.index');
    }

    public function data(Request $request)
    {
        $data = PeriodeLpm::query()->orderBy('dari', 'desc');
        return DataTables::of($data)
            ->addColumn('dari', function ($row) {
                return $row->dari->format('d M Y');
            })
            ->addColumn('sampai', function ($row) {
                return $row->sampai->format('d M Y');
            })
            ->addColumn('status', function ($row) {
                $badge = $row->status == 'aktif' ? 'bg-label-success' : 'bg-label-secondary';
                return '<span class="badge ' . $badge . '">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.periode-lpm.edit', ['periodeLpm' => $row->id]) . '">Edit</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record" method="POST" action="' . route('admin.periode-lpm.delete') . '">
                                        ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function add()
    {
        return view('admin.periode_lpm.add');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'dari' => 'required|date',
                'sampai' => 'required|date|after_or_equal:dari',
                'status' => 'required|in:aktif,tidak',
            ]);

            // If status is aktif, deactivate others
            if ($request->status == 'aktif') {
                PeriodeLpm::where('status', 'aktif')->update(['status' => 'tidak']);
            }

            PeriodeLpm::create($request->all());

            return redirect()->route('admin.periode-lpm.index')->with('success', 'Data berhasil ditambahkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(PeriodeLpm $periodeLpm)
    {
        return view('admin.periode_lpm.edit', compact('periodeLpm'));
    }

    public function update(PeriodeLpm $periodeLpm, Request $request)
    {
        try {
            $request->validate([
                'dari' => 'required|date',
                'sampai' => 'required|date|after_or_equal:dari',
                'status' => 'required|in:aktif,tidak',
            ]);

            // If status is aktif, deactivate others
            if ($request->status == 'aktif') {
                PeriodeLpm::where('id', '!=', $periodeLpm->id)->where('status', 'aktif')->update(['status' => 'tidak']);
            }

            $periodeLpm->update($request->all());

            return redirect()->route('admin.periode-lpm.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = PeriodeLpm::findOrFail($request->id);
            $data->delete();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
            ];
        } catch (\Throwable $th) {
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
            ];
        }
    }
}
