@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Sezione del Post -->
        <div class="post-section mb-3 p-4 border rounded bg-light">
            <h3 >Post di <strong class="text-primary">{{ $post->user->name }}</strong></h3>
            <div class="container mt-3 mb-3 px-3 py-2">
                <h1 class="text-wrap text-break">{{ $post->title }}</h1>
                <div class="container mt-3 mb-3 px-3 py-2">
                    <h3>{{ $post->content }}</h3>
                </div>
                <p>{{ \Carbon\Carbon::parse($post->updated_at)->timezone('Europe/Rome')->format('d/m/Y H:i') }}</p>
            </div>
        </div>
        <div class="add-comment-section mb-3 p-4 border rounded">
            <h3><strong>Commenta sotto questo post!</strong></h3>
            <form method="POST" action="{{ route('comments.store', $post) }}">
                @csrf
                <div class="container mt-3 mb-3 px-3 py-2">
                    <label><strong>Utente:</strong> {{ auth()->user()->name }}</label>
                    <input type="hidden" name="author" value="{{ auth()->user()->name }}">
                    <textarea name="text" class="form-control auto-expand mt-2" placeholder="Commenta qualcosa..."></textarea>
                    <!-- Mostra l'errore se presente -->
                    @error('text')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Invia!</button>
            </form>
        </div>
        <!-- Sezione dei Commenti -->
        <div class="comments-section mb-5 p-4 border rounded">
            <h3><strong>Commenti...</strong></h3>
            <div>
                @foreach($comments as $comment)
                    <div class=" container mt-3 mb-3 px-3 py-2 border-bottom">
                        <h3 ><strong class="text-primary">{{ $comment->author }}</strong></h3>
                        <div class="container mt-3 mb-3 px-3 py-2">
                            <h5 class="text-wrap text-break">{{ $comment->text }}</h5>
                        </div>
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

