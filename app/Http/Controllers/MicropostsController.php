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
        
        $path = '';    
        
        if (file_exists($request->image_url)){
            //$request->image_urlキーには画像ファイル？仮置き場のURL?画像ファイルの名前？が入っている
            
            //$path = Storage::disk('s3')->put('micropost_images', $request->image_url);
            //↑をしてbladeで{{ $micropost->image_url }}するのが一番近そう、画像URLは下記。バケット名はない。「storage」や無駄な「/」が出ない。
            //http://734d14（中略）9c2.vfs.cloud9.us-east-1.amazonaws.com/micropost_images/ssapT7FbZGbO9TlLf5Q3PYRxTQY7BAHEmS617Lyx.jpeg
            
            //↓S3に保存できず。S3に記載のオブジェクトURLを表示させたのと同じThis XML file does not appear to have any style information associated with it. エラー。
            //$image = $request->file('image_url'); //イメージ取得
            //Storage::disk('s3')->put('micropost_images', $image);
            //$url = Storage::disk('s3')->url($image);
            
            //↓過去に試して失敗したコード
            //$image = $request->file('image_url');
            //$path = Storage::disk('s3')->putFile('micropost_images', $image, 'public');
            //$path = $request->file('imge_url')->store('micropost_images');
            //$url = Storage::disk('s3')->url($path);
            
            //↓S3への保存だけは成功したコード
            //Storage::disk('s3')->put('micropost_images', $request->image_url);
            
            //↓成功コード
            $path = Storage::disk('s3')->put('micropost_images', $request->image_url, 'public');
            //$url = Storage::url($request->image_url);をすると表示された画像URLがhttp://734d14（中略）9c2.vfs.cloud9.us-east-1.amazonaws.com/storage//tmp/phpv2r6Zpに。storageら辺が変。
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
            //↓s3を消せないコード。エラーがでないからアクセスは出来ている？ローカル保存時は同じコードで消せた
            //Storage::delete($micropost->image_url);
            Storage::disk('s3')->delete($micropost->image_url);
        }

        return back();
    }    
}
