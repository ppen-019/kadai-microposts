<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; //0411追記

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $data = [];
        if (\Auth::check()) {
            //将来は月日が入力されていないときに年だけで、日付nullの時に年月だけで検索したい
            
            $from_year = $request->input('from_year');
            
            if ($request->input('from_month') <= 9) {
                $from_month = '0' . $request->input('from_month');    
            } else {
                $from_month = $request->input('from_month');
            }    
            
            if ($request->input('from_day') <= 9) {
                $from_day = '0' . $request->input('from_day');
            } else {
                $from_day = $request->input('from_day');
            }
            
            $date_from = $from_year . '-' . $from_month . '-' . $from_day . ' 00-00-00';
            
            $to_year = $request->input('to_year');
            
            if ($request->input('to_month') <= 9) {
                $to_month = '0' . $request->input('to_month');
            } else {
                $to_month = $request->input('to_month');
            }
            
            if ($request->input('to_day') <= 9) {
                $to_day = '0' . $request->input('to_day');
            } else {
                $to_day = $request->input('to_day');
            }
            
            $date_to = $to_year . '-' . $to_month . '-' . $to_day . ' 23-59-59';
            
            $user = \Auth::user();
            $keyword = $request->input('keyword');
            if(!empty($keyword)){
                $microposts = $user->feed_microposts()->whereBetween('created_at', [$date_from, $date_to])->where('content', 'like', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
            } else {
                $microposts = $user->feed_microposts()->whereBetween('created_at', [$date_from, $date_to])->orderBy('created_at', 'desc')->paginate(10);
            }
            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
            
            return view('search_result', $data);
            /*
            //全部入力されてたら
            if(!empty($keyword)&&!empty($datefrom)&&!empty($dateto)){
                
                $user = \Auth::user();
                $microposts = $user->feed_microposts()->where('content', 'like', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
                $data = [
                    'user' => $user,
                    'microposts' => $microposts,
                ];
                return view('search_result', $data);
            
                
            //検索ワードのみ入力されてたら
            } elseif(!empty($keyword)&&empty($datefrom)&&empty($dateto)) {
                $user = \Auth::user();
                $microposts = $user->feed_microposts()->where('content', 'like', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
                $data = [
                    'user' => $user,
                    'microposts' => $microposts,
                ];
                return view('search_result', $data);
            
            //日付のみフルで入力されてたら
            } elseif(empty($keyword)&&!empty($datefrom)&&!empty($dateto)) {
                $user = \Auth::user();
                $microposts = $user->feed_microposts()->where('content', 'like', '%'.$keyword.'%')->orderBy('created_at', 'desc')->paginate(10);
                $data = [
                    'user' => $user,
                    'microposts' => $microposts,
                ];
                return view('search_result', $data);
            
            //何も入力されてなかったら
            } elseif(empty($keyword)&&empty($datefrom)&&empty($dateto)) {    
                return back();
                
            //検索ワードと日付の片方だけ入力されてたら
            } else {    
                return back();
            }
            */
        }
    }
}
//まずは検索ワードの有無で分岐？
/*　検索ワード　有・無
　　開始日　　　有・無
　　終了日　　　有・無
　　→8通り
*/