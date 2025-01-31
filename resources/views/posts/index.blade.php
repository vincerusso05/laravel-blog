@extends('layouts.app')

@section('content')
    <div class="container">
        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Posta qualcosa!</a>
            <div class="container mt-3 mb-3 px-3 py-2">
                <h1>Lista dei post...</h1>
                <div class="list-group container mt-3 mb-3 px-3 py-2">
                    @foreach($posts as $post)
                        <div class="list-group-item container mt-2 mb-2 px-3 py-2 rounded border" id="post-{{ $post->id }}">
                            <input type="hidden" id="post-id" value="{{$post->id}}">
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
                                                @csrf

                                            <input type="button" class="btn btn-danger btn-sm deleteBtn" data-post-id="{{$post->id}}" value="Elimina">

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
    <script>
        $(document).ready(function () {
            $(document).on("click", ".deleteBtn", function () {
                let postId = $(this).data('post-id');
                var token = "{{ csrf_token() }}"; // CSRF Token
                var url = "{{ url('posts') }}/" + postId; // Costruisci l'URL DELETE

                Swal.fire({
                    title: "Sei sicuro di rimuovere il post?",
                    text: "Questa azione non può essere annullata!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sì, elimina!",
                    cancelButtonText: "Annulla"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: "POST",
                            data: {
                                _token: token,
                                _method: "DELETE"
                            },
                            success: function (response) {
                                Swal.fire("Eliminato!", "Il post è stato eliminato.", "success");
                                $("#post-" + postId).fadeOut(1500, function () { $(this).remove(); }); // Fade out e rimozione
                            },
                            error: function () {
                                Swal.fire("Errore", "Qualcosa è andato storto, riprova.", "error");
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection

