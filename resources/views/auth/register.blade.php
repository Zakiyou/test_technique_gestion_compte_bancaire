<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            background-color: #f4f7fa;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
        }
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .card-footer {
            border-radius: 0 0 10px 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .modal-footer {
            text-align: right;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header text-center">
            <h3>Inscription</h3>
        </div>
        <div class="card-body">
            <form id="registerForm">
                @csrf
                <!-- Nom -->
                <div class="form-group">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Entrez votre nom" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
                </div>

                <!-- Mot de passe -->
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Entrez votre mot de passe" required>
                </div>

                <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
            </form>
        </div>
        <div class="card-footer text-center">
            <p>Déjà inscrit? <a href="{{ route('login') }}">Se connecter</a></p>
        </div>
    </div>
</div>

<!-- Modal pour la vérification du code -->
<div class="modal" id="codeModal" tabindex="-1" role="dialog" aria-labelledby="codeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="codeModalLabel">Vérification de votre code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" id="verificationCode" class="form-control" placeholder="Entrez votre code de vérification" required>
                <input type="hidden" id="userIdField"> <!-- Champ caché pour l'ID de l'utilisateur -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                <button type="button" id="verifyCodeBtn" class="btn btn-primary">Vérifier le code</button>
            </div>
        </div>
    </div>
</div>


<script>
    $('#registerForm').on('submit', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            url: '/register',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Inscription réussie',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        $('#codeModal').modal('show');
                        $('#userIdField').val(response.data.user_id);
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON.message || 'Une erreur est survenue.';
                Swal.fire({
                    title: 'Erreur',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    });

    $('#verifyCodeBtn').on('click', function() {
        var code = $('#verificationCode').val();
        var userId = $('#userIdField').val(); 

        $.ajax({
            url: '/verify-code',
            method: 'POST',
            data: {
                user_id: userId,
                code: code,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Succès',
                        text: response.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.href = '/dashboard';  
                    });
                } else {
                    Swal.fire({
                        title: 'Erreur',
                        text: response.message,
                        icon: 'error'
                    });
                }
            },
            error: function(xhr) {
                var errorMessage = xhr.responseJSON.message || 'Le code est incorrect.';
                Swal.fire({
                    title: 'Erreur',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
