<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\News;
use App\Models\NewsComments;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::query();

        $news->where('status', 'published');
        if ($request->category) {
            $news->whereHas('categories', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }

        if ($request->search) {
            $news->where('title', 'like', '%' . $request->search . '%');
        }

        $news->orderBy('id', 'desc');
                                                        // $news = $news->with('categories')->get();
        $news = $news->with('categories')->paginate(5); // Paginate 10 items per page

        $author   = User::find(1);
        $category = Category::all();

        $recentNews = News::where('status', 'published')->orderBy('id', 'desc')->limit(3)->get();
        return view('news.index', compact('news', 'author', 'category', 'request', 'recentNews'));
    }

    public function detail(Request $request, $slug)
    {
        $news     = News::where('slug', $slug)->where('status', 'published')->firstOrFail();
        $author   = User::find(1);
        $category = Category::all();

        $recentNews = News::orderBy('id', 'desc')->limit(3)->get();

        $rememberUser = Cookie::get('remember_user_comment');
        $userComment  = null;
        if ($rememberUser) {
            $userComment = json_decode($rememberUser, true);
        }

        $category = Category::all();

        $recentNews = News::where('status', 'published')->orderBy('id', 'desc')->limit(3)->get();
        return view('news.detail.index', compact('news', 'author', 'category', 'request', 'recentNews', 'userComment', 'category', 'recentNews'));
    }

    public function storeComment(Request $request, News $news)
    {
        try {
            $request->validate([
                'name'              => 'required',
                'email'             => 'email|nullable',
                'content'           => 'required',
                'remember_me'       => 'nullable',
                'parent_comment_id' => 'nullable',
            ]);

            if (@$request->remember_me === "on") {
                $cookieName = 'remember_user_comment';
                $userData   = json_encode([
                    'name'  => $request->name,
                    'email' => $request->email,
                ]);

                $expiryTime = 60 * 24 * 30;

                Cookie::queue($cookieName, $userData, $expiryTime);
            } else {
                Cookie::queue(Cookie::forget('remember_user_comment'));
            }

            $newsComment = NewsComments::create([
                'news_id'           => $news->id,
                'name'              => $request->name,
                'email'             => $request->email,
                'content'           => $request->content,
                'parent_comment_id' => $request->parent_comment_id,
            ]);

            return redirect()->back()->with('success', 'Success add comment to the news')->withFragment('comment_' . $newsComment->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', implode(' ', collect($e->errors())->flatten()->toArray()));
        } catch (\Throwable $th) {
            \DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
