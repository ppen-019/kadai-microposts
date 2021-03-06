@extends('layouts.app')

@section('content')
    @if (Auth::check())
    <div class="mx-0 my-2 py-2 border" style="background-color:#f0f8ff">
        {!! Form::open(['route' => 'search.index']) !!}
            <div class="form-group d-flex flex-row my-auto justify-content-between">
                {!! Form::text('keyword', null, ['class' => 'form-control col-sm-4', 'placeholder' => '検索ワードを入力']) !!}
                {!! Form::label('from', 'From :', ['class' => 'text-right my-auto']) !!}
                <!--<label for="from">From</label>-->
                {!! Form::text('from',null, ['class' => 'form-control col-sm-2', 'id' => 'from']) !!}
                {!! Form::label('to', 'To :', ['class' => 'text-right my-auto']) !!}
                <!--<label for="to">to</label>-->
                {!! Form::text('to', date('Y-m-d'), ['class' => 'form-control col-sm-2', 'id' => 'to']) !!}
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block col-sm-2']) !!}
            </div>               
        {!! Form::close() !!} 
    </div>

        <!--きたない日付選択
        {!! Form::open(['route' => 'search.index']) !!}
            <div class="form-group d-flex flex-row">
                {!! Form::text('keyword', null, ['class' => 'form-control col-sm-3']) !!}
                <!--あとでデフォルトで今日を入れたい。カレンダーマークも入れたい
                投稿日：を改行されないようにしたい！年月日を高さ中央にしたい
                <span>投稿日:</span>
                    <?php $today = \Carbon\Carbon::now(); ?>
                    {!! Form::selectRange('from_year', 2019, 2020, '2019', ['class' => 'form-control']) !!}年
                    {!! Form::selectRange('from_month', 1, 12, '1', ['class' => 'form-control']) !!}月
                    {!! Form::selectRange('from_day', 1, 31, '1', ['class' => 'form-control']) !!}日
                    <span>〜</span>
                    {!! Form::selectRange('to_year', 2019, 2020, $today->year, ['class' => 'form-control']) !!}年
                    {!! Form::selectRange('to_month', 1, 12, $today->month, ['class' => 'form-control']) !!}月
                    {!! Form::selectRange('to_day', 1, 31, $today->day, ['class' => 'form-control']) !!}日
                {!! Form::submit('Search', ['class' => 'btn btn-primary btn-block col-sm-2']) !!}
            </div>               
        {!! Form::close() !!}
        -->
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
        <div class="row justify-content-sm-center">
            <h4>Micropostsはメッセージ投稿アプリです。<br>{!! link_to_route('signup.get', 'Sign up') !!}すると右記機能が使えます。</h4>
            <ul>
                <li>メッセージ・画像の投稿、編集、削除</li>
                <li>他のユーザーの投稿閲覧</li>
                <li>メッセージの検索</li>
                <li>他のユーザーのフォロー</li>
                <li>投稿のお気に入り登録</li>
            </ul>
        </div>
    @endif
@endsection
    <!--Bootstrap datepickerのコード
        <div class="form-group" id="datepicker-default">
          <label class="col-sm-3 control-label">Default</label>
          <div class="col-sm-9 form-inline">
            <div class="input-group date">
              <input type="text" class="form-control" value="20170621">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
            </div>
          </div>
        </div>
        -->