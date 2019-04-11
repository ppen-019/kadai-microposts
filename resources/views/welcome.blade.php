@extends('layouts.app')

@section('content')
    @if (Auth::check())
        {!! Form::open(['route' => 'search.index']) !!}
            <div class="form-group d-flex flex-row">
                {!! Form::text('keyword', null, ['class' => 'form-control']) !!}
                <!--代わりに日付検索。 {!! Form::file('image_url', ['class' => 'form-control col-sm-6']) !!}-->
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block col-sm-2']) !!}
            </div>
        {!! Form::close() !!}
        <div class="row">
            <aside class="col-sm-4">
                @include('users.card', ['user' => Auth::user()])
            </aside>
            <div class="col-sm-8">
                @if (Auth::id() == $user->id)
                    {!! Form::open(['route' => 'microposts.store', 'files' => true]) !!}
                        <div class="form-group">
                            {!! Form::textarea('content', old('content'), ['class' => 'form-control', 'rows' => '2']) !!}
                            <div class="d-flex flex-row">
                                {!! Form::file('image_url', ['class' => 'form-control col-sm-9']) !!}
                                {!! Form::submit('Post', ['class' => 'btn btn-primary btn-block col-sm-3']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                @endif
                @if (count($microposts) > 0)
                    @include('microposts.microposts', ['microposts' => $microposts])
                @endif
            </div>
        </div>
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Microposts</h1>
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection