@extends('layouts.single')
@section('title'){{$post->title}}@stop
@section('subheading'){{ \Illuminate\Support\Str::limit($post->content, 150) }}@stop
@section('content')
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <p>
                        {{$post->content}}
                    </p>
                </div>
            </div>
        </div>
    </article>

    <hr>
    @include('partials.comment')
    <hr>
@endsection
