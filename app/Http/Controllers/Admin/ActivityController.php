<?php

namespace App\Http\Controllers\Admin;

use Str;
use App\Models\Tag;
use App\Models\Unit;
use App\Models\Activity;
use App\Http\Services\ImageHelper;
use Illuminate\Http\Request;
use App\Http\Services\BulkData;
use Yajra\DataTables\DataTables;
use App\Http\Services\Summernote;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ActivityController extends Controller
{

    private $uploadDir = 'storage/image-activity/';

    public function index()
    {
        return view('admin.activity.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data   = Activity::join('users', 'users.id', '=', 'activities.author_id')
            ->select(
                'activities.*',
                'users.name as users_name',
                \DB::raw("COALESCE(
                    (SELECT GROUP_CONCAT(units.name SEPARATOR ';')
                     FROM activity_unit
                     JOIN units ON units.id = activity_unit.unit_id
                     WHERE activity_unit.activity_id = activities.id),
                    'No units') as unit"),
                \DB::raw("COALESCE(
                    (SELECT GROUP_CONCAT(tags.name SEPARATOR ';')
                     FROM activity_tag
                     JOIN tags ON tags.id = activity_tag.tag_id
                     WHERE activity_tag.activity_id = activities.id),
                    'No tags') as tag"),
            );
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('activities.slug', 'LIKE', "%$search%");
                    $query->orWhere('activities.title', 'LIKE', "%$search%");
                    $query->orWhere('users.name', 'LIKE', "%$search%");
                });
            })
            ->editColumn('unit', function ($row) {
                $unit     = explode(';', $row->unit);
                $response = '';
                foreach ($unit as $key => $value) {
                    $response .= '<span class="badge bg-secondary me-1 my-1">' . $value . '</span>';
                }
                return $response;
            })
            ->editColumn('tag', function ($row) {
                $tag      = explode(';', $row->tag);
                $response = '';
                foreach ($tag as $key => $value) {
                    $response .= '<span class="badge bg-secondary me-1 my-1">' . $value . '</span>';
                }
                return $response;
            })
            ->editColumn('body', function ($row) {
                if (! $row->body) {
                    return '-';
                }
                $cleanedHtml = Summernote::clean($row->body);
                $response    = Str::limit($cleanedHtml, 200, '...');
                if (! $response) {
                    return '-';
                }
                return $response;
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'published') {
                    return '<span class="badge bg-success">' . $row->status . '</span>';
                }
                return '<span class="badge bg-secondary">' . $row->status . '</span>';
            })
            ->addColumn('action', function ($row) {
                $shareLink     = route('addactivity.index', ['code' => $row->code]);
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item edit-record-button" href="javascript:void(0)" onclick="shareLink(\'' . $shareLink . '\')">Share Link</a></li>
                                <li>
                                <li>
                                    <a class="dropdown-item edit-record-button" href="' . $shareLink . '" >Buka Link</a></li>
                                    <div class="dropdown-divider"></div>
                                <li>
                                <li>
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.activity.edit', ['activity' => $row->id]) . '">Edit</a></li>
                                <li>
                                    <form class="form-delete-record">
                                    ' . method_field('DELETE') . csrf_field() . '
                                        <input type="hidden" name="id" value="' . $row->id . '">
                                        <input type="hidden" name="title" value="' . $row->title . '">
                                        <button type="submit" class="dropdown-item text-danger">
                                            Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>';
                return $actionButtons;
            })
            ->rawColumns(['action', 'body', 'unit', 'status', 'tag'])
            ->toJson();
    }

    public function add()
    {
        $unit = Unit::all();
        $tag  = Tag::all();
        return view('admin.activity.add.index', compact('unit', 'tag'));
    }

    public function store(Request $request)
    {
        $paths = [];
        try {
            \DB::beginTransaction();
            $request->validate([
                'unit_id'      => 'required|array',
                'tags'         => 'required',
                'title'        => 'required',
                'body'         => 'nullable',
                'published_at' => 'nullable',
                'status'       => 'nullable',
                'image'        => 'nullable|mimes:jpeg,png,jpg,gif,webp,ico',
            ]);

            $tags = json_decode($request->tags);

            $slug         = Str::slug($request->title);
            $originalSlug = $slug;

            $counter = 1;
            while (Activity::where('slug', $slug)->exists()) {
                $slug = "{$originalSlug}_{$counter}";
                $counter++;
            }

            $body = $request->body;
            if ($request->body) {
                $generate = Summernote::generate($request->body, BulkData::dirSummernote);
                foreach ($generate['paths'] as $keyPath => $path) {
                    $paths[] = $path;
                }
                $body = $generate['data'];
            }

            $activity        = new Activity();
            $activity->title = $request->title;
            $activity->body  = $body;

            // if ($request->hasFile('image')) {
            //     $file = $request->file('image');

            //     $originalName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            //     $sanitizedName = Str::slug($originalName, '-');
            //     $ext           = $file->getClientOriginalExtension() ?: 'jpg';

            //     $filename = uniqid() . '-' . $sanitizedName . '.' . $ext;
            //     $uploadDir = public_path($this->uploadDir);
            //     $file->move($uploadDir, $filename);

            //     $activity->image = $filename;
            // } elseif ($request->filled('image_name')) {
            //     $data = $request->input('image_name');

            //     if (is_string($data) && strpos($data, 'data:image') === 0) {
            //         if (preg_match('#^data:(image/[^;]+);base64,(.+)$#', $data, $m)) {
            //             $mime = $m[1];
            //             $bin  = base64_decode($m[2]);

            //             $ext = 'jpg';
            //             if (strpos($mime, 'jpeg') !== false) $ext = 'jpg';
            //             elseif (strpos($mime, 'png') !== false) $ext = 'png';
            //             elseif (strpos($mime, 'webp') !== false) $ext = 'webp';
            //             elseif (strpos($mime, 'gif') !== false) $ext = 'gif';
            //             elseif (strpos($mime, 'ico') !== false) $ext = 'ico';

            //             $filename = 'base64_' . uniqid() . '.' . $ext;
            //             $destinationPath = public_path($this->uploadDir) . '/' . $filename;
            //             File::put($destinationPath, $bin);

            //             $activity->image = $filename;
            //         }
            //     }
            // }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $compressedUrl = ImageHelper::compressUploadedImage($file, $this->uploadDir);
               
                $activity->image = basename(parse_url($compressedUrl, PHP_URL_PATH));
            } elseif ($request->filled('image_name')) {
                $data = $request->input('image_name');
                $compressedUrl = ImageHelper::saveBase64Image($data, 'image', $this->uploadDir);

                $activity->image = basename(parse_url($compressedUrl, PHP_URL_PATH));
            }

            $activity->slug         = $slug;
            $activity->code         = \Crypt::encryptString($activity->id);
            $activity->author_id    = \Auth::user()->id;
            $activity->published_at = $request->published_at;
            $activity->status       = $request->status;
            $activity->save();

            $tagId = [];
            foreach ($tags as $key => $value) {
                $tag     = Tag::firstOrCreate(['name' => $value->value]);
                $tagId[] = $tag->id;
            }
            foreach ($request->unit_id as $key => $value) {
                $activity->unit()->sync($value);
            }
            $activity->tag()->sync($tagId);

            \DB::commit();
            return redirect()->route('admin.activity.index')->with('success', 'Berhasil menambahkan data activity baru dengan judul ' . $request->title);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.activity.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();;
        } catch (\Throwable $th) {
            \DB::rollback();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.activity.add')->with('error', $th->getMessage())->withInput();;
        }
    }

    public function edit(Activity $activity)
    {
        $unit = Unit::all();
        $tag  = Tag::all();
        return view('admin.activity.edit.index', compact('unit', 'activity', 'tag'));
    }

    public function update(Activity $activity, Request $request)
    {
        $paths = [];
        try {
            \DB::beginTransaction();
            $request->validate([
                'unit_id'      => 'required|array',
                'tags'         => 'required',
                'title'        => 'required',
                'slug'         => 'required',
                'body'         => 'nullable',
                'published_at' => 'nullable',
                'status'       => 'nullable',
                'image'        => 'nullable|mimes:jpeg,png,jpg,gif,webp,ico',
            ]);

            $tags = json_decode($request->tags);

            $slug         = Str::slug($request->title);
            $originalSlug = $slug;

            $counter = 1;
            while (Activity::where('slug', $slug)->where('id', '!=', $activity->id)->exists()) {
                $slug = "{$originalSlug}_{$counter}";
                $counter++;
            }

            if (! $request->image_name) {
                $oldImagePath = public_path('storage/image-activity/' . $activity->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath); // Remove the old image
                }
                $activity->image = null;
            }

            if ($request->hasFile('image')) {
                $oldImagePath = public_path('storage/image-activity/' . $activity->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath); // Remove the old image
                }

                // $fileName        = uniqid() . '-' . $request->file('image')->getClientOriginalName();
                // $destinationPath = public_path($this->uploadDir);
                // if (! File::exists($destinationPath)) {
                //     File::makeDirectory($destinationPath, 0755, true);
                // }

                // $request->file('image')->move($destinationPath, $fileName);
                // $activity->image = $fileName;
                $compressedUrl = ImageHelper::compressUploadedImage($request->file('image'), $this->uploadDir);
                $activity->image = basename(parse_url($compressedUrl, PHP_URL_PATH));
            }

            $body = $request->body;
            if ($request->body) {
                $generate = Summernote::generateFOrEdit($activity->body, $request->body, BulkData::dirSummernote);
                // dd($generate, $request->body);
                foreach ($generate['paths'] as $keyPath => $path) {
                    $paths[] = $path;
                }
                $body = $generate['data'];
            }

            $activity->title        = $request->title;
            $activity->body         = $body;
            $activity->slug         = $slug;
            $activity->author_id    = \Auth::user()->id;
            $activity->published_at = $request->published_at;
            $activity->status       = $request->status;
            $activity->save();

            $tagId = [];
            foreach ($tags as $key => $value) {
                $tag     = Tag::firstOrCreate(['name' => $value->value]);
                $tagId[] = $tag->id;
            }
            foreach ($request->unit_id as $key => $value) {
                $activity->unit()->sync($value);
            }
            $activity->tag()->sync($tagId);

            \DB::commit();
            return redirect()->route('admin.activity.index')->with('success', 'Berhasil mengedit data activity dengan judul ' . $request->title);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.activity.edit', ['activity' => $activity->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.activity.edit', ['activity' => $activity->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $data = Activity::findOrFail($request->id);
            $data->tag()->detach();
            $data->unit()->detach();

            $data->delete();

            \DB::commit();
            return [
                'status'  => true,
                'type'    => 'success',
                'message' => 'Success',
                'request' => $request->all(),
            ];
        } catch (\Throwable $th) {
            \DB::rollback();
            return [
                'status'  => false,
                'type'    => 'error',
                'message' => $th->getMessage(),
                'request' => $request->all(),
            ];
        }
    }
}
