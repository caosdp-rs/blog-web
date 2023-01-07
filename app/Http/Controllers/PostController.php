<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;

class PostController extends Controller
{
    // get all posts
    public function index()
    {
        return response([
            'posts' => Post::orderBy('created_at', 'desc')->with('user:id,name,image')->withCount('comments', 'likes')->get()
        ], 200);
    }

    // get single posts
    public function show($id)
    {
        return response([
            'posts' => Post::where('id', $id)->withCount('comments', 'like')->get()
        ], 200);
    }

    // create a post
    public function store(Request $request)
    {
        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post = Post::create([
            'body' => $attrs['body'],
            'user_id' => auth()->user()->id
        ]);

        // for now sku for post image

        return response([
            'message' => 'Post criado com sucesso',
            'post' => $post
        ], 200);
    }

    // update a post
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post não encontrado'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Você não pode alterar este post'
            ], 403);
        }
        
        //validate fields
        $attrs = $request->validate([
            'body' => 'required|string'
        ]);

        $post->update([
            'body' => $attrs['body'],
        ]);

        // for now sku for post image

        return response([
            'message' => 'Post atualizado com sucesso',
            'post' => $post
        ], 200);
    }

    // delete a post
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response([
                'message' => 'Post não encontrado'
            ], 403);
        }
        if ($post->user_id != auth()->user()->id) {
            return response([
                'message' => 'Você não pode excluir este post'
            ], 403);
        }
        $post->comments()->delete();
        $post->likes()->delete();
        $post->delete();
        return response([
            'message'=>'Post deletado com sucesso.'
        ],200);
    }
}
