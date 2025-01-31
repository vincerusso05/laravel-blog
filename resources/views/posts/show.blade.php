@extends('layouts.app')

@section('content')
    <style>
        textarea {
            resize: none;
        }

        .edit-icon i {
            color: #007bff; /* Colore della matita */
            font-size: 18px;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .edit-icon i:hover {
            color: #0056b3; /* Colore al passaggio del mouse */
        }

        /* Commento visibile quando non in modifica */
        .comment-text:disabled {
            background-color: #f7f7f7;  /* Colore di sfondo per gli input disabilitati */
        }

        /* Nasconde il campo di modifica fino a quando non clicchi sulla matita */
        .edit-input.d-none {
            display: none;
        }

        /* Mostra il commento modificato quando è in stato disabilitato */
        .comment-text {
            display: inline-block;
        }

    </style>
    <div class="container">
        <!-- Sezione del Post -->
        <div class="post-section mb-3 p-4 border rounded bg-light">
            <h3 ><strong>Post di </strong><strong class="text-primary">{{ $post->user->name }}</strong></h3>
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
                    <input type="hidden" name="author" value="{{ auth()->user()->name }}">
                    <textarea  rows="10" cols="40" name="text" class="form-control auto-expand mt-2 " placeholder="Commenta qualcosa..."></textarea>
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
                    <div id="comment-{{ $comment->id }}" class="container mt-3 mb-3 px-3 py-2 border-bottom">
                        <h3><strong class="text-primary">{{ $comment->author }}</strong></h3>
                        <div class="container mt-3 mb-3 px-3 py-2">
                            <!-- Commento visualizzato in un input text visibile -->
                            <input type="text" class="form-control comment-text" value="{{ $comment->text }}" disabled>
                            <!-- Campo per modificare il commento (visibile quando clicchi sulla matita) -->
                            <input type="text" class="form-control edit-input d-none" value="{{ $comment->text }}">
                        </div>
                        <small>
                            <strong>Commentato il:</strong> {{ \Carbon\Carbon::parse($comment->updated_at)->timezone('Europe/Rome')->format('d/m/Y H:i') }}
                        </small>
                        <!-- Icona modifica (solo per l'autore) -->
                        @if(auth()->id() === $comment->user_id)
                            <span class="edit-icon float-end" data-comment-id="{{ $comment->id }}">
                                <i class="fa-solid fa-pencil"></i>
                            </span>
                            <input type="button" class="btn btn-primary btn-sm d-none save-edit" data-comment-id="{{ $comment->id }}" value="Salva modifica">
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Quando clicco sulla matita
            $(".edit-icon").click(function () {
                let commentId = $(this).data("comment-id");
                let commentContainer = $("#comment-" + commentId);

                // Nasconde l'input di commento (comment-text) e mostra l'input di modifica (edit-input)
                commentContainer.find(".comment-text").addClass("d-none");  // Nasconde commento
                commentContainer.find(".edit-input").removeClass("d-none").focus();  // Mostra input di modifica
                commentContainer.find(".save-edit").removeClass("d-none");  // Mostra il pulsante Salva
            });

            // Quando clicco su "Salva modifica"
            $(".save-edit").click(function () {
                let commentId = $(this).data("comment-id");
                let commentContainer = $("#comment-" + commentId);
                let newText = commentContainer.find(".edit-input").val();

                // Rimuovi eventuali errori precedenti
                commentContainer.find('.text-danger').remove();

                // Esegui la richiesta AJAX per aggiornare il commento
                $.ajax({
                    url: "/comments/" + commentId,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "PUT",
                        text: newText
                    },
                    success: function (response) {
                        // Success: Aggiorna il commento
                        Swal.fire("Modificato!", "Il commento è stato aggiornato.", "success");

                        // Nascondi l'input di modifica e mostra l'input di commento aggiornato
                        commentContainer.find(".comment-text").val(newText).removeClass("d-none").prop("disabled", true);  // Mostra commento aggiornato
                        commentContainer.find(".edit-input").addClass("d-none");  // Nascondi input di modifica
                        commentContainer.find(".save-edit").addClass("d-none");  // Nascondi il pulsante Salva
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            // Gli errori di validazione sono contenuti in xhr.responseJSON.errors
                            var errors = xhr.responseJSON.errors;

                            // Mostra gli errori di validazione
                            $.each(errors, function (field, messages) {
                                var errorMessage = messages.join(', ');

                                // Trova il campo di input e aggiungi l'errore
                                commentContainer.find(".edit-input").after('<div class="text-danger">' + errorMessage + '</div>');
                            });
                        } else {
                            // Gestisci altri tipi di errore
                            Swal.fire("Errore", "Non è stato possibile aggiornare il commento.", "error");
                        }
                    }
                });
            });
        });

    </script>

@endsection

