<?php

namespace App\Http\Controllers\Admin;

use App\Blog;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BlogsController extends Controller

{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $posts = Blog::where('user_id', '=', ($request->get('user_id') ? $request->get('user_id') : Auth::user()->id))
            ->with('with_comments')
            ->orderBy('created_at', 'desc')->paginate(10);
        $title = 'Your Posts';
        return view('admin.blogs.index', compact('posts', 'title'));
    }

    public function create()
    {
        $title = 'New Blog';
        return view('admin.blogs.created', compact('title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:blogs|max:255',
            'content' => 'required'
        ]);

        $blog = new Blog();
        $blog->user_id = ($request->input('user_id') ? $request->input('user_id') : Auth::user()->id);
        $blog->title = $request->input('title');
        $blog->slug = $this->slugify($request->input('title'));
        $blog->content = $request->input('content');

        if ($blog->save()) {
            $status = 'success';
            $message = trans('New blog has been added successfully!');
        } else {
            $status = 'error';
            $message = trans('Ops! Something went wrong please try again.');
        }
        return redirect('/admin/blogs?user_id=' . $blog->user_id)->with('message', $message);
    }

    public function edit($id)
    {
        $title = trans('Update Blog');
        $blog = Blog::findOrFail($id);
        return view('admin.blogs.edit', compact('blog', 'title'));
    }

    public function update($id, Request $request)
    {

        $this->validate($request, [
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        $blog = Blog::findOrFail($id);
        $blog->user_id = ($request->input('user_id') ? $request->input('user_id') : Auth::user()->id);
        $blog->title = $request->input('title');
        $blog->slug = $this->slugify($request->input('title'));
        $blog->content = $request->input('content');

        if ($blog->update()) {
            $status = 'success';
            $message = trans('Blog has been updated successfully!');
        } else {
            $status = 'error';
            $message = trans('Ops! Something went wrong please try again.');
        }
        return redirect('/admin/blogs?user_id=' . $blog->user_id)->with('message', $message);
    }

    public function destroy(Request $request, $id)
    {
        $status = 'error';
        $message = trans('Ops! Something went wrong please try again.');
        if (!empty($id)) {
            $blog = Blog::findOrFail($id);
            if (!empty($blog)) {
                Blog::destroy($id);
                $status = 'success';
                $message = trans('Blog has been successfully deleted.');
            }
        }
        return redirect('/admin/blogs?user_id=' . ($request->input('user_id') ? $request->input('user_id') : Auth::user()->id))->with('message', $message);
    }

    protected function slugify($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }
}
