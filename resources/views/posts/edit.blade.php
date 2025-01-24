@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($post) ? 'Modifica Post' : 'Crea Nuovo Post' }}</h1>
        <form method="POST" action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}">
            @csrf
            @if(isset($post))
                @method('PUT')
            @endif
            <div class="mb-3">
                <label>Titolo</label>
                <input type="text" name="title" class="form-control" value="{{ $post->title ?? '' }}" required>
            </div>
            <div class="mb-3">
                <label>Contenuto</label>
                <textarea name="content" class="form-control" required>{{ $post->content ?? '' }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Salva</button>
        </form>
    </div>
@endsection
