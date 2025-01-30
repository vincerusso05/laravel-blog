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
                                <h4><strong class="text-primary">{{ $post->user->name }}</strong>'s post</h4>
                                <h5 class="text-wrap text-break">{{ $post->title }}</h5>
                                <h6>{{ \Carbon\Carbon::parse($post->updated_at)->timezone('Europe/Rome')->format('d/m/Y H:i') }}</h6>
                                <p>Commenti: {{ $post->comments_count }}</p>
                            </div>
                        </a>
                        <div>
                            <div class="position-absolute top-0 end-0 mt-2 me-2">
                                @if(auth()->id() === $post->user_id)
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-primary btn-sm ms-2">Modifica</a>
                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Sei sicuro di voler eliminare questo post?')">Elimina</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        {{ $posts->links('pagination::bootstrap-4') }}
    </div>
@endsection
