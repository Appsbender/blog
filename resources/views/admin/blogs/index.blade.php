@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    @if(!empty($posts))
                        <h2>Your Post</h2>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($posts as $blog)
                                <tr>
                                    <th scope="row">{{$blog->id}}</th>
                                    <td>{{$blog->title}}</td>
                                    <td>{{$blog->created_at}}</td>
                                    <td>
                                        <a href="/admin/blogs/{{$blog->id}}/edit" class="pull-left"
                                           style="margin-right: 5px; display: inline-block; border: none;">Edit</a>

                                        {!! Form::open(array('class' => 'form-inline', 'method' => 'DELETE', 'route' => array('admin.blogs.destroy', $blog->id), 'onsubmit' => 'ConfirmDelete()')) !!}
                                        {!! csrf_field() !!}
                                        {!! Form::submit('Delete', array('class' => 'btn btn-danger','style'=>"background: none; padding: 0; margin: 0; display: inline-block; border: none; color: #23527c;")) !!}
                                        {!! Form::close() !!}

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        function ConfirmDelete() {
            var x = confirm("Are you sure you want to delete?");
            if (x)
                return true;
            else
                return false;
        }
    </script>
@endsection