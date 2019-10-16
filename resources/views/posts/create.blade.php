@extends('layouts.app')
@section('content')
    <h1>Create Post</h1>
    <!-- Using LaravelCollective For Form Html -->
    <!-- Opening The Form -->
    {!! Form::open(['action' => 'PostsController@store','method' => 'post','enctype' => 'multipart/form-data']) !!}
        <!-- Start The Title Part -->
        <div class="form-group">
            {{Form::label('title', 'Title')}} <!-- First is name And Second is Value-->
            {{Form::text('title','',['class' => 'form-control','placeholder' => 'Title'])}} <!-- title => name  | class => specify class Of The Form Input  -->
        </div>
        <!-- Start The Body Part -->
        <div class="form-group">
            {{Form::label('body', 'Body')}}
            {{Form::textarea('body','',['class' => 'form-control','placeholder' => 'Body Text'])}} <!-- title => body  | class => specify class Of The Form Input  -->
        </div>
        <!-- Start The Part Of Uploading File -->
        <div class="form-group">
            {{Form::file('cover_image')}}
        </div>
        <!-- Start The Submit Part -->
            {{Form::submit('Create Post',['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

@endsection
