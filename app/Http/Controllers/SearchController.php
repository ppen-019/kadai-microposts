<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //0411追記

class SearchController extends Controller
{
    public function index(Request $request)
    {
        //$microposts = DB::table('microposts');
        //全マイクロポストを取得

        $data = [];
        if (\Auth::check()) {
            $keyword = $request->input('keyword');
            if(!empty($keyword)){
                $user = \Auth::user();
                $microposts = $user->feed_microposts()->where('content', 'like', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
                $data = [
                    'user' => $user,
                    'microposts' => $microposts,
                ];
                return view('search_result', $data);
            } else {
                return back();
            }
        }
    }
}
