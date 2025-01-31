@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Lista dei post...</h1>
            <div class="container mt-3 mb-3 px-3 py-2">
                <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Posta qualcosa!</a>
                <div class="list-group container mt-3 mb-3 px-3 py-2">
                    @foreach($posts as $post)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                                    <div>
                                        <h4>Post di <strong class="text-primary">{{ $post->user->name }}</strong></h4>

                                        <div class="container mt-3 mb-3 px-3 py-2">
                                            <div class="post-container">
                                                <h5 class="text-wrap text-break mb-3">{{ $post->title }}</h5>
                                                <span>{{ \Carbon\Carbon::parse($post->updated_at)->timezone('Europe/Rome')->format('d/m/Y H:i') }}</span>

                                            </div>
                                        </div>
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
            </div>


        <div class="d-flex justify-content-center vh-100">

                {{ $posts->links('pagination::bootstrap-4') }}

        </div>



    </div>
@endsection
