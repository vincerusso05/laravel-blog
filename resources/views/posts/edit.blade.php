@extends('layouts.app')

@section('content')
    <style>
        textarea {
            resize: none;
        }
    </style>
    <div class="container">
        <h1>{{ isset($post) ? 'Modifica il tuo post...' : 'Crea il tuo post...' }}</h1>
        <div class="container mt-3 mb-3 px-3 py-2">
            <form id="editForm" method="POST" action="{{ route('posts.update', $post) }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Titolo del post</label>
                    <input type="text" name="title" class="form-control auto-expand" placeholder="A cosa stai pensando?" value="{{ $post->title ?? '' }}" required>
                    @error('title')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label>Descrizione</label>
                    <textarea rows="10" cols="40" name="content" class="form-control auto-expand" placeholder="Scrivi qualcosa..." required>{{ $post->content ?? '' }}</textarea>
                    @error('content')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <input type="button" class="btn btn-primary" id="editBtn" value="Salva!">
            </form>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('#editBtn').click(function (e) {
                e.preventDefault();  // Impedisce l'invio del form tradizionale

                // Prepara i dati del form
                var formData = {
                    title: $('input[name="title"]').val(),
                    content: $('textarea[name="content"]').val(),
                    _token: $('input[name="_token"]').val(), // CSRF token
                };

                // URL dell'update
                var url = "{{ route('posts.update', $post) }}";

                // Esegui la richiesta AJAX
                $.ajax({
                    url: url,
                    type: 'PUT',  // Metodo HTTP per l'aggiornamento
                    data: formData,
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                title: response.message,  // Mostra il messaggio di successo
                                icon: "success",
                                draggable: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect_url;  // Redirect alla pagina index
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        // Gestisci gli errori di validazione
                        if (xhr.status === 422) {
                            // Gli errori di validazione sono contenuti in xhr.responseJSON.errors
                            var errors = xhr.responseJSON.errors;

                            // Rimuovi gli errori precedenti
                            $('.text-danger').remove();

                            // Mostra i nuovi errori vicino ai campi
                            $.each(errors, function(field, messages) {
                                // Trova il campo di input e aggiungi l'errore
                                var errorMessage = messages.join(', ');
                                $('[name="' + field + '"]').after('<div class="text-danger">' + errorMessage + '</div>');
                            });
                        } else {
                            // Gestisci altri tipi di errore
                            Swal.fire({
                                title: "Errore!",
                                text: "Qualcosa è andato storto. Riprova!",
                                icon: "error",
                            });
                        }
                    }
                });
            });
        });

    </script>
@endsection
