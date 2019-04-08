<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;//0407追記

class MicropostsController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $microposts = $user->feed_microposts()->orderBy('created_at', 'desc')->paginate(10);
            
            $data = [
                'user' => $user,
                'microposts' => $microposts,
            ];
        }
        
        return view('welcome', $data);
    }
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required_without:image_url|max:191',
            'image_url' => 'required_without:content|image|file',
        ]);
        
        if (file_exists($request->image_url)){
            $path = $request->file('image_url')->store('public/micropost_images');  
        }
        
        $request->user()->microposts()->create([
            'content' => $request->content,
            'image_url' => $path,
        ]);
        
        return back();
    }
    
    public function destroy($id)
    {
        $micropost = \App\Micropost::find($id);

        if (\Auth::id() === $micropost->user_id) {
            $micropost->delete();
            Storage::delete($micropost->image_url);
        }

        return back();
    }    
}
