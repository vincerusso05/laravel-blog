@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Sezione del Post -->
        <div class="post-section mb-5 p-4 border rounded bg-light">
            <h4 ><strong class="text-primary">{{ $post->user->name }}</strong>'s post</h4>
            <h1>{{ $post->title }}</h1>
            <h3>{{ $post->content }}</h3>
            <p><strong>Post creato il:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</p>

        </div>

        <!-- Sezione dei Commenti -->
        <div class="comments-section mb-5 p-4 border rounded">
            <h1>Commenti</h1>
            <div>
                @foreach($comments as $comment)
                    <div class="mb-3 p-3 border-bottom">
                        <h3 ><strong class="text-primary">{{ $comment->author }}</strong></h3>
                        <h5>{{ $comment->text }}</h5>
                        <small>
                            <strong>Commentato il:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}
                            <br>
                        </small>
                    </div>
                @endforeach
            </div>
        </div>


        <div class="add-comment-section p-4 border rounded">
            <h3>Aggiungi Commento</h3>
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf

                <div>
                    <label><strong>Autore:</strong> {{ auth()->user()->name }}</label>
                    <input type="hidden" name="author" value="{{ auth()->user()->name }}">
                </div>
                <div class="mb-3">
                    <label>Testo</label>
                    <textarea name="text" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Aggiungi</button>
            </form>
        </div>
    </div>
@endsection
