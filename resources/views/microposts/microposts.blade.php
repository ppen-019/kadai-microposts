<ul class="list-unstyled">
    @foreach ($microposts as $micropost)
        <li class="media mb-3">
            <img class="mr-2 rounded" src="{{ Gravatar::src($micropost->user->email, 50) }}" alt="">
            <div class="media-body">
                <div>
                    {!! link_to_route('users.show', $micropost->user->name, ['id' => $micropost->user->id]) !!} <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                </div>
                <div>
                    @if ($micropost->content)
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                    @endif
                </div>
                <div>
                    @if ($micropost->image_url)
                        <!-- ↓成功したコード-->
                        <p><img src="{{ env('IMAGE_URL') . $micropost->image_url }}" height="150" class="mt-3"></p>
                        <!--<p><img src="{{ Storage::url($micropost->image_url) }}" height="150"></p>-->
                        <!-- ↑表示されない-->
                        <!-- ↑を{{ $micropost->image_url }}に変えても表示されない-->
                        <!-- ↑を{{ asset($micropost->image_url) }}に変えても表示されない-->
                    @endif
                </div>
                <div class="d-flex flex-row mx-2">
                    @include('favorite.favorite_button', ['micropost' => $micropost])
                    @if (Auth::id() == $micropost->user_id)
                        {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
        </li>
    @endforeach
</ul>
{{ $microposts->render('pagination::bootstrap-4') }}