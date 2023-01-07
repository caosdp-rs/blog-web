<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class CommentController extends Controller
{
    // get  all comments of a post
    public function index($id)
    {
        $post = Post::find($id);
        if (!$post)
        {
            return response([
                'message' => 'Post não encontrado.'
            ], 403);
        }
        return response([
            'comments'=>$post->comments()->with('user:id,name,image')->get()
        ],200);
    }

    // create a comment
    public function store(Request $request, $id)
    {
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'message' => 'Post não encontrado'
            ],403);
        }

        // validate fields
        $attrs = $request->validate([
            'comment'=>'required|string'
        ]);

        Comment::create([
            'comment'=>$attrs['comment'],
            'post_id'=>$id,
            'user_id'=>auth()->user()->id
        ]);

        return response([
            'message'=>'Comentário salvo com sucesso.'
        ],200);
    }

    // update a comentário
    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        
        if(!$comment)
        {
            return response([
                'message' => 'Comentário não encontrado'
            ],403);
        }
        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permissão negada.'
            ],403);

            // validate fields
            $attrs = $request->validate([
                'comment'=>'required|string'
            ]);

            $comment->update([
                'comment'=>$attrs['comment']
            ]);

            return response([
                'message'=>'Comentário alterado com sucesso'
            ],200);
        }
    }

    // delete a comentário
    public function destroy($id)
    {
        $comment = Comment::find($id);
        
        if(!$comment)
        {
            return response([
                'message' => 'Comentário não encontrado'
            ],403);
        }
        if($comment->user_id != auth()->user()->id)
        {
            return response([
                'message' => 'Permissão negada.'
            ],403);

            $comment->delete();

            return response([
                'message'=>'Comentário deletado com sucesso'
            ],200);
        }
    }
}
