@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <h3>{{ $post->content }}</h3>
        <h6>Post creato il:{{ $post->created_at->format('d/m/Y H:i') }}</h6>
        <h6>Post aggiornato il:{{ $post->updated_at->format('d/m/Y H:i') }}</h6>

        <h2>Commenti</h2>
        <div>
            @foreach($comments as $comment)
                <div>
                    <strong>{{ $comment->author }}</strong>
                    <h5>{{ $comment->text }}</h5>
                    <p><strong>Commentato il:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Commento modificato il:</strong> {{ $comment->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            @endforeach
        </div>

        <h3>Aggiungi Commento</h3>
        <form method="POST" action="{{ route('comments.store', $post) }}">
            @csrf
            <div class="mb-3">
                <label>Autore</label>
                <input type="text" name="author" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Testo</label>
                <textarea name="text" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Aggiungi</button>
        </form>
    </div>
@endsection
