@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ isset($post) ? 'Modifica il tuo post...' : 'Crea il tuo post...' }}</h1>
            <div class="container mt-3 mb-3 px-3 py-2">
                <form method="POST" action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}">
                    @csrf
                    @if(isset($post))
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label>Titolo del post</label>
                        <input type="text" name="title" class="form-control auto-expand"  placeholder="A cosa stai pensando?" value="{{ $post->title ?? '' }}" required>
                        @error('title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label>Descrizione</label>
                        <textarea name="content" class="form-control auto-expand" placeholder="Scrivi qualcosa..." required>{{ $post->content ?? '' }}</textarea>
                        @error('content')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Salva!</button>
                </form>
            </div>
    </div>
@endsection

