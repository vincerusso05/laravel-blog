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
                <form method="POST" action="{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}">
                    @csrf
                    @if(isset($post))
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label>Titolo del post</label>
                        <input type="text" name="title" class="form-control auto-expand" placeholder="A cosa stai pensando?" {{ old('title', $post->title ?? '') }}" required>
                        @error('title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Descrizione</label>
                        <textarea rows="10" cols="40" name="content" class="form-control auto-expand" placeholder="Scrivi qualcosa..." required>{{ old('content', $post->content ?? '') }}</textarea>
                        @error('content')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <input type="button" class="btn btn-primary" id="submitBtn" value="Invia!">
                </form>
            </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#submitBtn').click(function (e) {
                e.preventDefault();  // Evita il comportamento di submit predefinito del form

                // Prepara i dati del form
                var formData = {
                    title: $('input[name="title"]').val(),
                    content: $('textarea[name="content"]').val(),
                    _token: $('input[name="_token"]').val(), // CSRF token
                };

                // Determina l'URL per il salvataggio
                var url = "{{ isset($post) ? route('posts.update', $post) : route('posts.store') }}";
                var method = "{{ isset($post) ? 'PUT' : 'POST' }}";

                // Esegui la richiesta AJAX
                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    success: function (response) {
                        if(response.success) {
                            // Quando il server risponde positivamente
                            Swal.fire({
                                title: response.message,  // Mostra il messaggio di successo
                                icon: "success",
                                draggable: true,
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Quando l'utente clicca OK nell'alert, reindirizza alla pagina index
                                    window.location.href = response.redirect_url;  // Ritorna alla rotta index
                                }
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        // Gestisci gli errori di validazione
                        if (xhr.status === 422) {
                            // Gli errori di validazione sono contenuti in xhr.responseJSON.errors
                            var errors = xhr.responseJSON.errors;

                            // Rimuovi errori precedenti
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
