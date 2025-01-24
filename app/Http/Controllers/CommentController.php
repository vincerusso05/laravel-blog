<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'author' => 'required|max:255',
            'text' => 'required',
        ]);

        $post->comments()->create($request->all());

        return redirect()->route('posts.show', $post)
            ->with('success', 'Commento aggiunto con successo.');
    }


}
