<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with('user')->withCount('comments')->orderBy('updated_at','desc')->paginate(20);
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255|regex:/\S+/',
            'content' => 'required|max:2000|regex:/\S+/',
        ], [
            'title.regex' => 'Il titolo non può essere composto solo da spazi.',
            'content.regex' => 'Il contenuto non può essere composto solo da spazi.',
            'title.max' => 'Hai raggiunto i caratteri massimi per il titolo',
            'content.max' => 'Hai raggiunto i caratteri massimi per il post'
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->get('content'),
            'user_id' => auth()->id(),
        ]);

        // Risposta AJAX con successo
        return response()->json([
            'success' => true,
            'message' => 'Post creato con successo.',
            'redirect_url' => route('posts.index')  // URL di redirect per la pagina index
        ]);
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $comments = $post->comments()->orderBy('created_at','desc')->get();
        return view('posts.show', compact('post', 'comments'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Non autorizzato.');
        }

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Post $post)
    {
        if (auth()->id() !== $post->user_id) {
            abort(403, 'Non sei autorizzato a modificare questo post.');
        }
        $request->validate([
            'title' => 'required|max:255|regex:/\S+/',
            'content' => 'required|max:2000|regex:/\S+/',
        ], [
            'title.regex' => 'Il titolo non può essere composto solo da spazi.',
            'content.regex' => 'Il contenuto non può essere composto solo da spazi.',
            'title.max' => 'Hai raggiunto i caratteri massimi per il titolo',
            'content.max' =>'Hai raggiunto i caratteri massimi per il post'// Messaggio personalizzato
        ]);

        $post->update($request->all());
        return response()->json([
            'success' => true,
            'message' => 'Post aggiornato con successo.',
            'redirect_url' => route('posts.index'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'Non autorizzato.');
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post eliminato con successo.');
    }
}
