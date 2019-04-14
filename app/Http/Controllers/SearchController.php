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
            
  
            $user = \Auth::user();
            $keyword = $request->input('keyword');
            $date_from = $request->input('from');
            $date_to = $request->input('to');
            
            //開始日の入力があれば時間を足す。
            if(!empty($date_from)){
                $date_from .= ' 00:00:00';
            }

            if(!empty($request->input('to'))){
                $date_to .= ' 23:59:59';
            }
            
            if(empty($keyword)&&empty($date_from)&&empty($date_to)){
                return back();
            } else {
                $microposts = $user->feed_microposts();    
                
                if(!empty($keyword)){
                    $microposts = $microposts->where('content', 'like', '%'.$keyword.'%');
                }
                
                if(!empty($date_from)){
                    $microposts = $microposts->where('created_at', '>=', $date_from);
                }
                
                if(!empty($date_to)){
                    $microposts = $microposts->where('created_at', '<=', $date_to);
                }
                
                $microposts = $microposts->orderBy('created_at', 'desc')->paginate(10);
                
                $data = [
                    'user' => $user,
                    'microposts' => $microposts,
                ];
                
                return view('search_result', $data);                
            }
        }
    }
}

/*    
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
*/  