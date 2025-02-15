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
            'author' => 'required|regex:/\S+/',
            'text' => 'required|max:2000|regex:/\S+/',
        ], [
            'author.regex' => 'Il titolo non può essere composto solo da spazi.',
            'text.regex' => 'Il contenuto non può essere composto solo da spazi.',
            'author.max' => 'Hai raggiunto i caratteri massimi per il titolo',
            'text.max' =>'Hai raggiunto i caratteri massimi per il post'// Messaggio personalizzato
        ]);

        $post->comments()->create([
            'author' => $request->author,
            'text' => $request->text,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('posts.show', $post)
            ->with('success', 'Commento aggiunto con successo.');
    }
    public function update(Request $request, Comment $comment)
    {
        // Controlla che l'utente sia il proprietario
        if (auth()->id() !== $comment->user_id) {
            return response()->json(['error' => 'Non puoi modificare questo commento'], 403);
        }

        // Validazione
        $request->validate([

            'text' => 'required|max:2000|regex:/\S+/',
        ], [

            'text.regex' => 'Il contenuto non può essere composto solo da spazi.',
            'text.max' =>'Hai raggiunto i caratteri massimi per il post'
        ]);

        // Aggiorna il commento
        $comment->text = $request->text;
        $comment->save();

        return response()->json(['message' => 'Commento aggiornato con successo']);
    }

}
