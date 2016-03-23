<?php

namespace App\Http\Controllers\Api;

use App\Blog;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApisController extends Controller
{

    public function index(Request $request)
    {
        if (!empty($request->get('token'))) {
            JWTAuth::setToken($request->get('token'))->authenticate();
            $posts = Blog::with('with_comments')->orderBy('created_at', 'desc')->get();
            return Response::json(array('status' => 'success', 'posts' => $posts), 200);
        } else {
            return Response::json(array('status' => 'error', 'message' => 'Forbidden'), 403);
        }
    }

    public function add_content(Request $request)
    {
        JWTAuth::setToken($request->input('token'))->authenticate();

        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:blogs|max:255',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json(array('status' => 'error', 'message' => 'Bad Request'), 400);
        }

        $blog = new Blog();
        $blog->user_id = ($request->input('user_id') ? $request->input('user_id') : Auth::user()->id);
        $blog->title = $request->input('title');
        $blog->slug = $this->slugify($request->input('title'));
        $blog->content = $request->input('content');

        if ($blog->save()) {
            $status = 'success';
            $message = 'New blog has been added successfully!';
            $code = 200;
        } else {
            $status = 'error';
            $message = 'Bad Request';
            $code = 400;
        }
        return Response::json(array('status' => $status, 'message' => $message), $code);
    }


    public function edit_content(Request $request, $id)
    {
        JWTAuth::setToken($request->input('token'))->authenticate();

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return Response::json(array('status' => 'error', 'message' => 'Bad Request'), 400);
        }

        $blog = Blog::findOrFail($id);
        if (empty($blog)) {
            return Response::json(array('status' => 'error', 'message' => 'Bad Request'), 400);
        };
        $blog->user_id = ($request->input('user_id') ? $request->input('user_id') : Auth::user()->id);
        $blog->title = $request->input('title');
        $blog->slug = $this->slugify($request->input('title'));
        $blog->content = $request->input('content');

        if ($blog->update()) {
            $status = 'success';
            $message = 'Blog has been updated successfully!';
            $code = 200;
        } else {
            $status = 'error';
            $message = 'Bad Request';
            $code = 400;
        }
        return Response::json(array('status' => $status, 'message' => $message), $code);
    }

    public function remove_content(Request $request, $id)
    {
        JWTAuth::setToken($request->input('token'))->authenticate();
        $status = 'error';
        $message = 'Bad Request.';
        $code = 400;
        if (!empty($id)) {
            $blog = Blog::findOrFail($id);
            if (!empty($blog)) {
                Blog::destroy($id);
                $status = 'success';
                $message = 'Blog has been successfully deleted.';
                $code = 200;
            }
        }
        return Response::json(array('status' => $status, 'message' => $message), $code);
    }

    protected function slugify($string)
    {
        return strtolower(trim(preg_replace('~[^0-9a-z]+~i', '-', html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', htmlentities($string, ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8')), '-'));
    }
}
