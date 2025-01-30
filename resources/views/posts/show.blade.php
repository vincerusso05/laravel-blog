@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Sezione del Post -->
        <div class="post-section mb-3 p-4 border rounded bg-light">
            <h4 ><strong class="text-primary">{{ $post->user->name }}</strong>'s post</h4>
            <h1 class="text-wrap text-break">{{ $post->title }}</h1>
            <h3>{{ $post->content }}</h3>
            <p>{{ \Carbon\Carbon::parse($post->updated_at)->timezone('Europe/Rome')->format('d/m/Y H:i') }}</p>

        </div>
        <div class="add-comment-section mb-3 p-4 border rounded">
            <h3>Aggiungi Commento</h3>
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf

                <div>
                    <label><strong>Autore:</strong> {{ auth()->user()->name }}</label>
                    <input type="hidden" name="author" value="{{ auth()->user()->name }}">
                </div>
                <div class="mb-3">
                    <label>Testo</label>
                    <textarea name="text" class="form-control auto-expand" required></textarea>
                    <!-- Mostra l'errore se presente -->
                    @error('text')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Aggiungi</button>
            </form>
        </div>
        <!-- Sezione dei Commenti -->
        <div class="comments-section mb-5 p-4 border rounded">
            <h1>Commenti</h1>
            <div>
                @foreach($comments as $comment)
                    <div class="mb-3 p-3 border-bottom">
                        <h3 ><strong class="text-primary">{{ $comment->author }}</strong></h3>
                        <h5 class="text-wrap text-break">{{ $comment->text }}</h5>
                        <small>
                            <strong>Commentato il:</strong> {{ \Carbon\Carbon::parse($comment->created_at)->timezone('Europe/Rome')->format('d/m/Y H:i') }}
                            <br>
                        </small>
                    </div>
                @endforeach
            </div>
        </div>



    </div>
@endsection

