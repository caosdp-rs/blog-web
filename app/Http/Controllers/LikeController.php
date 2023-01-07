<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;

class LikeController extends Controller
{
    // like or unlike
    public function likeOrUnlike($id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message'=>'Post não encontrado.'
            ],403);
        }

        $like = $post->likes()->where('user_id',auth()->user()->id)->first();

        // if not liked then like
        if(!$like)
        {
            Like::create([
                'post_id'=>$id,
                'user_id'=>auth()->user()->id
            ]);
            return response([
                'message'=>'Post curtido.'
            ],200);
        }
        // else dislike it
        $like->delete(); 
        return response([
            'message'=>'Deslike.'
        ],200);
    }
}
