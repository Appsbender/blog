@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @if(!empty($posts))
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    @foreach($posts as $blog)
                        <div class="post-preview">
                            <a href="/contents/view/{{$blog->slug}}">
                                <h2 class="post-title">
                                    {{$blog->title}}
                                </h2>

                                <h3 class="post-subtitle">
                                    {{ \Illuminate\Support\Str::limit($blog->content, 150) }}
                                </h3>
                            </a>

                            <p class="post-meta">Posted by
                                <a href="#">
                                    {{ucfirst($blog->with_users->first_name)}}
                                    {{ucfirst($blog->with_users->last_name)}}
                                </a>
                                on {{date('F d, Y', strtotime($blog->created_at))}}</p>
                        </div>
                        <hr>
                    @endforeach

                                <!-- Pager -->
                        <?php echo $posts->render(); ?>
                       {{-- <ul class="pager">
                            <li class="next">
                                <a href="#">Older Posts &rarr;</a>
                            </li>
                        </ul>--}}
                </div>
                @endif
        </div>
    </div>

    <hr>
@endsection
