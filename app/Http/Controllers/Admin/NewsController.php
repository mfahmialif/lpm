<?php

namespace App\Http\Controllers\Admin;

use Str;
use App\Models\News;
use App\Models\Category;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Services\BulkData;
use Yajra\DataTables\DataTables;
use App\Http\Services\Summernote;
use App\Http\Services\ImageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{

    private $uploadDir = 'storage/image-news/';

    public function index()
    {
        return view('admin.news.index');
    }

    public function data(Request $request)
    {
        $search = request('search.value');
        $data   = News::join('users', 'users.id', '=', 'news.author_id')
            ->select(
                'news.*',
                'users.name as users_name',
                \DB::raw("COALESCE(
                    (SELECT GROUP_CONCAT(categories.name SEPARATOR ';')
                     FROM news_categories
                     JOIN categories ON categories.id = news_categories.category_id
                     WHERE news_categories.news_id = news.id),
                    'No categories') as categories")
            );
        return DataTables::of($data)
            ->filter(function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhere('news.slug', 'LIKE', "%$search%");
                    $query->orWhere('news.title', 'LIKE', "%$search%");
                    $query->orWhere('users.name', 'LIKE', "%$search%");
                });
            })
            ->editColumn('categories', function ($row) {
                $categories = explode(';', $row->categories);
                $response   = '';
                foreach ($categories as $key => $value) {
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
                $actionButtons = '
                        <div class="d-inline-block">
                            <a href="javascript:;" class="btn btn-sm btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical ti-md"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end m-0">
                                <li>
                                    <a class="dropdown-item edit-record-button" href="' . route('admin.news.edit', ['news' => $row->id]) . '">Edit</a></li>
                                    <div class="dropdown-divider"></div>
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
            ->rawColumns(['action', 'body', 'categories', 'status'])
            ->toJson();
    }

    public function add()
    {
        $categories = Category::all();
        return view('admin.news.add.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $paths = [];
        try {
            \DB::beginTransaction();
            $request->validate([
                'categories'   => 'nullable',
                'title'        => 'required',
                'body'         => 'nullable',
                'published_at' => 'nullable',
                'status'       => 'nullable',
                'image'        => 'nullable|mimes:jpeg,png,jpg,gif,webp,ico',
            ]);

            $slug         = Str::slug($request->title);
            $originalSlug = $slug;

            $counter = 1;
            while (News::where('slug', $slug)->exists()) {
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

            $news        = new News();
            $news->title = $request->title;
            $news->body  = $body;

            // if ($request->hasFile('image')) {
            //     $file = $request->file('image');

            //     $originalName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            //     $sanitizedName = Str::slug($originalName, '-');
            //     $ext           = $file->getClientOriginalExtension() ?: 'jpg';

            //     $filename = uniqid() . '-' . $sanitizedName . '.' . $ext;
            //     $uploadDir = public_path($this->uploadDir);
            //     $file->move($uploadDir, $filename);

            //     $news->image = $filename;
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

            //             $news->image = $filename;
            //         }
            //     }
            // }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $compressedUrl = ImageHelper::compressUploadedImage($file, $this->uploadDir);

                $news->image = basename(parse_url($compressedUrl, PHP_URL_PATH));
            } elseif ($request->filled('image_name')) {
                $data = $request->input('image_name');
                $compressedUrl = ImageHelper::saveBase64Image($data, 'image', $this->uploadDir);

                $news->image = basename(parse_url($compressedUrl, PHP_URL_PATH));
            }

            $news->slug         = $slug;
            $news->author_id    = \Auth::user()->id;
            $news->published_at = $request->published_at;
            $news->status       = $request->status;
            $news->save();

            if ($request->filled('categories')) {
                $categories = json_decode($request->categories);
                $categoryId = [];
                foreach ($categories as $key => $value) {
                    $category     = Category::firstOrCreate(['name' => $value->value]);
                    $categoryId[] = $category->id;
                }
                $news->categories()->sync($categoryId);
            }
            \DB::commit();
            return redirect()->route('admin.news.index')->with('success', 'Berhasil menambahkan data news baru dengan judul ' . $request->title);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.news.add')->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.news.add')->with('error', $th->getMessage())->withInput();
        }
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        return view('admin.news.edit.index', compact('categories', 'news'));
    }

    public function update(News $news, Request $request)
    {
        $paths = [];
        try {
            \DB::beginTransaction();
            $request->validate([
                'categories'   => 'nullable',
                'title'        => 'required',
                'slug'         => 'required',
                'body'         => 'nullable',
                'published_at' => 'nullable',
                'status'       => 'nullable',
                'image'        => 'nullable|mimes:jpeg,png,jpg,gif,webp,ico',
            ]);

            $slug         = Str::slug($request->title);
            $originalSlug = $slug;

            $counter = 1;
            while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                $slug = "{$originalSlug}_{$counter}";
                $counter++;
            }

            if (! $request->image_name) {
                $oldImagePath = public_path('storage/image-news/' . $news->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath); // Remove the old image
                }
                $news->image = null;
            }

            if ($request->hasFile('image')) {
                $oldImagePath = public_path('storage/image-news/' . $news->image);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath); // Remove the old image
                }

                // $fileName        = uniqid() . '-' . $request->file('image')->getClientOriginalName();
                // $destinationPath = public_path($this->uploadDir);
                // if (! File::exists($destinationPath)) {
                //     File::makeDirectory($destinationPath, 0755, true);
                // }

                // $request->file('image')->move($destinationPath, $fileName);
                // $news->image = $fileName;
                $compressedUrl = ImageHelper::compressUploadedImage($request->file('image'), $this->uploadDir);
                $news->image = basename(parse_url($compressedUrl, PHP_URL_PATH));
            }

            $body = $request->body;
            if ($request->body) {
                $generate = Summernote::generateFOrEdit($news->body, $request->body, BulkData::dirSummernote);
                foreach ($generate['paths'] as $keyPath => $path) {
                    $paths[] = $path;
                }
                $body = $generate['data'];
            }

            $news->title        = $request->title;
            $news->body         = $body;
            $news->slug         = $slug;
            $news->author_id    = \Auth::user()->id;
            $news->published_at = $request->published_at;
            $news->status       = $request->status;
            $news->save();

            if ($request->filled('categories')) {
                $categories = json_decode($request->categories);
                $categoryId = [];
                foreach ($categories as $key => $value) {
                    $category     = Category::firstOrCreate(['name' => $value->value]);
                    $categoryId[] = $category->id;
                }
                $news->categories()->sync($categoryId);
            }
            \DB::commit();
            return redirect()->route('admin.news.index')->with('success', 'Berhasil mengedit data news dengan judul ' . $request->title);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.news.edit', ['news' => $news->id])->with('error', implode('<br><br>', array_map('implode', $e->errors())))->withInput();
        } catch (\Throwable $th) {
            \DB::rollback();
            Summernote::deleteImageFromPaths(@$paths);
            return redirect()->route('admin.news.edit', ['news' => $news->id])->with('error', $th->getMessage())->withInput();
        }
    }

    public function delete(Request $request)
    {
        try {
            \DB::beginTransaction();
            $request->validate([
                'id' => 'required',
            ]);

            $data = News::findOrFail($request->id);
            $data->newsLike->each(function ($like) {
                $like->delete();
            });

            $data->newsCategory->each(function ($category) {
                $category->delete();
            });

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
