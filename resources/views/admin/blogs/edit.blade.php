@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">New Blog</div>
                    <div class="panel-body">
                        {!! Form::model($blog, ['url' => ['admin/blogs', $blog->id], 'method' => 'PATCH']) !!}
                            {!! csrf_field() !!}
                            <input type="hidden" value="{{$blog->id}}">
                            <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                <label class="col-md-3 control-label">Title</label>

                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="title" value="{{$blog->title}}">

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                <label class="col-md-3 control-label">Content</label>

                                <div class="col-md-8">
                                    <textarea class="form-control" name="content" rows="5">{{$blog->content}}</textarea>

                                    @if ($errors->has('title'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('content') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-sign-in"></i>Update
                                    </button>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection