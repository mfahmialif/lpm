<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProdiUnit;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProdiUnitController extends Controller
{
    public function index()
    {
        return view('admin.prodi-unit.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data = ProdiUnit::select('prodi_units.*')
            ->selectRaw('(SELECT COUNT(*) FROM user_prodi_unit WHERE user_prodi_unit.prodi_unit_id = prodi_units.id AND user_prodi_unit.jenis = "editor") as jumlah_editor')
            ->selectRaw('(SELECT COUNT(*) FROM user_prodi_unit WHERE user_prodi_unit.prodi_unit_id = prodi_units.id AND user_prodi_unit.jenis = "audity") as jumlah_audity');
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('nama', 'LIKE', "%$search%");
                    $query->orWhere('keterangan', 'LIKE', "%$search%");
                });
            })
            ->addColumn('action', function ($row) {
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item" href="' . route('admin.prodi-unit.users', ['prodiUnit' => $row->id]) . '"><i class="ti ti-users me-1"></i> Kelola User</a>
                                </li>
                                <div class="dropdown-divider"></div>
                                <li>
                                    <button class="dropdown-item edit-record-button"
                                        data-id="' . $row->id . '"
                                        data-nama="' . $row->nama . '"
                                        data-keterangan="' . $row->keterangan . '"
                                        >Edit</button></li>
                                <div class="dropdown-divider"></div>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="nama" value="' . $row->nama . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function store(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'nama' => 'required',
                'keterangan' => 'nullable'
            ]);

            $dataStore = new ProdiUnit();
            $dataStore->nama = $request->nama;
            $dataStore->keterangan = $request->keterangan;
            $dataStore->save();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan data ' . $request->nama
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function update(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
                'nama' => 'required',
                'keterangan' => 'nullable'
            ]);

            $dataStore = ProdiUnit::findOrFail($request->id);
            $dataStore->nama = $request->nama;
            $dataStore->keterangan = $request->keterangan;
            $dataStore->save();

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Success'
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $dataStore = ProdiUnit::findOrFail($request->id);
            $dataStore->delete();

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

    public function users(ProdiUnit $prodiUnit)
    {
        return view('admin.prodi-unit.users', compact('prodiUnit'));
    }

    public function usersData(ProdiUnit $prodiUnit)
    {
        $search = request('search.value');
        $data = $prodiUnit->users()
            ->select('users.*', 'user_prodi_unit.created_at as assigned_at', 'user_prodi_unit.jenis');

        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('users.name', 'LIKE', "%$search%");
                    $query->orWhere('users.email', 'LIKE', "%$search%");
                    $query->orWhere('users.username', 'LIKE', "%$search%");
                });
            })
            ->addColumn('jenis_badge', function ($row) {
                $badge = $row->jenis == 'editor'
                    ? '<span class="badge bg-label-primary">Editor</span>'
                    : '<span class="badge bg-label-warning">Audity</span>';
                return $badge;
            })
            ->addColumn('action', function ($row) use ($prodiUnit) {
                return '
                    <form class="form-remove-user">
                    ' . method_field('DELETE') . csrf_field() . '
                        <input type="hidden" name="user_id" value="' . $row->id . '">
                        <input type="hidden" name="prodi_unit_id" value="' . $prodiUnit->id . '">
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="ti ti-trash"></i> Hapus
                        </button>
                    </form>';
            })
            ->rawColumns(['action', 'jenis_badge'])
            ->toJson();
    }

    public function searchUsers(Request $request)
    {
        $term = $request->get('term');
        $prodiUnitId = $request->get('prodi_unit_id');

        $users = User::where(function ($query) use ($term) {
            $query->where('name', 'LIKE', "%$term%")
                ->orWhere('email', 'LIKE', "%$term%")
                ->orWhere('username', 'LIKE', "%$term%");
        })
            ->whereDoesntHave('prodiUnits', function ($query) use ($prodiUnitId) {
                $query->where('prodi_units.id', $prodiUnitId);
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'username']);

        $results = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'value' => $user->name,
                'label' => $user->name . ' (' . $user->email . ') - @' . $user->username,
            ];
        });

        return response()->json($results);
    }

    public function addUser(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'prodi_unit_id' => 'required|exists:prodi_units,id',
                'jenis' => 'required|in:editor,audity',
            ]);

            $prodiUnit = ProdiUnit::findOrFail($request->prodi_unit_id);

            // Check if already exists
            if ($prodiUnit->users()->where('users.id', $request->user_id)->exists()) {
                return [
                    'status' => false,
                    'type' => 'error',
                    'message' => 'User sudah terdaftar di prodi unit ini'
                ];
            }

            $prodiUnit->users()->attach($request->user_id, ['jenis' => $request->jenis]);

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menambahkan user sebagai ' . ucfirst($request->jenis)
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }

    public function removeUser(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'prodi_unit_id' => 'required|exists:prodi_units,id',
            ]);

            $prodiUnit = ProdiUnit::findOrFail($request->prodi_unit_id);
            $prodiUnit->users()->detach($request->user_id);

            \DB::commit();
            return [
                'status' => true,
                'type' => 'success',
                'message' => 'Berhasil menghapus user dari prodi unit'
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status' => false,
                'type' => 'error',
                'message' => $th->getMessage()
            ];
        }
    }
}
