<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Comment;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{
    public function store(Request $request)
    {
        Log::info($request->all());
        $this->validate($request, [
            'blog_id' => 'required',
            'content' => 'required'
        ]);

        $blog = Blog::findOrFail($request->input('blog_id'));

        $comment = new Comment();
        $comment->user_id = ($request->input('user_id') ? $request->input('user_id') : Auth::user()->id);
        $comment->blog_id = $blog->id;
        $comment->content = $request->input('content');

        if ($comment->save()) {
            $status = 'success';
            $message = trans('Comment has been added successfully!');
        } else {
            $status = 'error';
            $message = trans('Ops! Something went wrong please try again.');
        }
        return redirect('/contents/view/'.$blog->slug)->with('message', $message);
    }
}
