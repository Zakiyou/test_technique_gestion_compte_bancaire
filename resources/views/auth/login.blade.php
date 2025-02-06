<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            font-family: Arial, sans-serif;
        }

        .login-container {
            max-width: 450px;
            margin: 5% auto;
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .login-container h3 {
            color: #343a40;
            margin-bottom: 30px;
        }

        .form-label {
            color: #495057;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }

        .card-footer {
            background: none;
            border-top: none;
            text-align: center;
        }

        .card-footer a {
            color: #007bff;
        }

        .card-footer a:hover {
            text-decoration: underline;
        }

        .modal-content {
            border-radius: 10px;
        }

        .modal-body p {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <h3 class="text-center">Connexion</h3>
        <form id="login-form">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        <div class="card-footer">
            <p>Pas encore inscrit ? <a href="{{ route('Acceuil') }}">S'inscrire</a></p>
        </div>
    </div>

    <!-- Modal pour entrer le code de vérification -->
    <div class="modal fade" id="verificationModal" tabindex="-1" aria-labelledby="verificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="verificationModalLabel">Vérification de votre compte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Un code de vérification a été envoyé à votre email. Veuillez le saisir ci-dessous pour activer votre compte.</p>
                    <div class="mb-3">
                        <label for="verification-code" class="form-label">Code de vérification</label>
                        <input type="text" class="form-control" id="verification-code" name="verification-code" required>
                    </div>
                    <button type="button" class="btn btn-primary w-100" id="verify-code">Vérifier le code</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
    $('#login-form').submit(function (event) {
        event.preventDefault();
        const email = $('#email').val();
        const password = $('#password').val();

        $.ajax({
            url: '/login',
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                email: email,
                password: password,
            },
            success: function (response) {
                if (response.success) { 
                    if (response.status_code === 200) {
                        window.location.href = '/dashboard';
                    } else if (response.status_code === 202) {
                        $('#verificationModal').modal('show');
                        $('#verification-code').data('user-id', response.data.user_id);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message || 'Une erreur est survenue.',
                    });
                }
            },
            error: function (xhr) {
                let response = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: response ? response.message : 'Une erreur est survenue lors de la connexion.',
                });
            }
        });
    });

    $('#verify-code').click(function () {
        const code = $('#verification-code').val();
        const userId = $('#verification-code').data('user-id');

        $.ajax({
            url: '/verify-code',
            method: 'POST',
            data: {
                _token: $('input[name="_token"]').val(),
                code: code,
                user_id: userId,
            },
            success: function (response) {
                if (response.success) { 
                    Swal.fire({
                        icon: 'success',
                        title: 'Compte vérifié',
                        text: response.message || 'Votre compte a été vérifié avec succès.',
                    }).then(() => {
                        window.location.href = '/dashboard';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Code incorrect',
                        text: response.message || 'Le code de vérification est incorrect.',
                    });
                }
            },
            error: function (xhr) {
                let response = xhr.responseJSON;
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: response ? response.message : 'Une erreur est survenue lors de la vérification.',
                });
            }
        });
    });
});

    </script>

</body>

</html>
