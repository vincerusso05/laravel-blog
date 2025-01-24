@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista dei Post</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Crea Nuovo Post</a>
        <div class="list-group">
            @foreach($posts as $post)
                <a href="{{ route('posts.show', $post) }}" class="list-group-item">
                    <h5>{{ $post->title }}</h5>
                    <h6>Postato il:{{ $post->created_at->format('d/m/Y H:i') }}</h6>
                    <h6>Post modificato    il:{{ $post->updated_at->format('d/m/Y H:i') }}</h6>
                    <p>Commenti: {{ $post->comments_count }}</p>
                </a>
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">Modifica</a>
                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo post?')">Elimina</button>
                </form>
            @endforeach
        </div>
        {{ $posts->links() }}
    </div>
@endsection
