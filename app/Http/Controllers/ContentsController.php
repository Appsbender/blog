<?php

namespace App\Http\Controllers;

use App\Blog;
use Illuminate\Http\Request;

use App\Http\Requests;

class ContentsController extends Controller
{
    public function index()
    {
        $posts = Blog::with('with_comments', 'with_users')->orderBy('created_at', 'desc')->paginate(10);
        $title = 'Your Posts';
        return view('contents.index', compact('posts', 'title'));
    }

    public function view($slug)
    {
        if (empty($slug)) {
            return redirect('/');
        }
        $post = Blog::where('slug', '=', $slug)->with(['with_comments'=>function($q){
            $q->with('with_users')->get();
        }])->first();
        return view('contents.view', compact('post'));
    }

}
