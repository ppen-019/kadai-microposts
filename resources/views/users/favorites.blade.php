@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            @include('users.card', ['user' => $user])
        </aside>
        <div class="col-sm-8">
            @include('users.navtabs', ['user' => $user])
        <div>
            <ul class="list-unstyled">
                @foreach ($favorites as $favorite)
                <li class="media mb-3">
                    <img class="mr-2 rounded" src="{{ Gravatar::src($user->email, 50) }}" alt="">
                    <div class="media-body">
                        <div>
                            {!! link_to_route('users.show', $favorite->user_id, ['id' => $favorite->user_id]) !!} <span class="text-muted">posted at {{ $favorite->created_at }}</span>
                        <!-- ↑怪しい！！！！！-->
                        </div>
                        <div>
                            <p class="mb-0">{!! nl2br(e($favorite->content)) !!}</p>
                        </div>
                        <div class="d-flex flex-row mx-2">
                            @include('favorite.favorite_button', ['micropost' => $favorite])
                            @if (Auth::id() == $favorite->user_id)
                                {!! Form::open(['route' => ['microposts.destroy', $favorite->micropost_id], 'method' => 'delete']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            @endif
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
            {{ $favorites->render('pagination::bootstrap-4') }}
            </ul>
        </div>
    </div>
@endsection