<div class="row">
    <div class="container">
        <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
            @if(count($post->with_comments) > 0)
                @foreach($post->with_comments as $comment)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <small style="font-size: small; font-style: italic; color: #949494;">
                                Commented by
                                <b>
                                    {{$comment->with_users->first_name}}
                                    {{$comment->with_users->last_name}}
                                </b>
                                last on {{date('F d, Y', strtotime($comment->created_at))}}
                            </small>
                            <br/>
                            {{$comment->content}}
                        </div>
                    </div>
                @endforeach
            @endif
            <hr/>
            {!! Form::open(['route' => 'comments.store']) !!}
            {!! csrf_field() !!}
            <input type="hidden" name="blog_id" value="{{$post->id}}">

            <div class="form-group form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                <label class="control-label">Content</label>
                <textarea class="form-control" name="content" rows="5"></textarea>

                <div class="clearfix"></div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-btn fa-sign-in"></i>Save
                </button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>