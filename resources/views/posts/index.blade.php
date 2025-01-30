@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista dei Post</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Crea Nuovo Post</a>
        <div class="list-group">
            @foreach($posts as $post)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                            <div>
                                <h5>{{ $post->title }}</h5>
                                <h6>Postato il: {{ $post->created_at->format('d/m/Y H:i') }}</h6>
                                <h6>Post modificato il: {{ $post->updated_at->format('d/m/Y H:i') }}</h6>
                                <p>Commenti: {{ $post->comments_count }}</p>
                            </div>
                        </a>
                        <div>
                            @if(auth()->id() === $post->user_id)
                            <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-success btn-sm ms-2">Modifica</a>
                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo post?')">Elimina</button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $posts->links() }}
    </div>
@endsection
