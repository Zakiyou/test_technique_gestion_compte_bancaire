@extends('admin.app')

@section('titre', "Liste des comptes bancaires")

@section('contenu')
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Mes comptes bancaires</h4>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 text-right">
                        <!-- Bouton pour ouvrir le modal d'ajout -->
                        <button class="btn btn-primary" data-toggle="modal" data-target="#ajouterCompteModal">
                            Ajouter un compte bancaire
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table des comptes bancaires -->
            <div class="card-box mb-30">
                <div class="pb-20">
                    <table class="data-table table stripe hover nowrap">
                        <thead>
                            <tr>
                                <th>Identifiant</th>
                                <th>Intitulé du compte</th>
                                <th>Numéro de compte</th>
                                <th>Solde</th>
                                <th>Statut</th>
                                <th>Date de création du compte</th>
                                <th class="datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody id="comptesList">
                            @forelse($comptes as $compte)
                            <tr>
                                <td>{{ $compte->id }}</td>
                                <td>{{ $compte->titulaire_compte }}</td>
                                <td>{{ $compte->numero_compte }}</td>
                                <td>{{ number_format($compte->solde, 2) }} €</td>
                                <td>{{$compte->statut}} </td>

                                <td>{{ $compte->created_at->format('d-m-Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <!-- Voir opération -->
                                            <a class="dropdown-item" href="{{ route('operations.index', $compte->id) }}">
                                                <i class="fas fa-arrow-up"></i> Voir Opérations
                                            </a>
                                            
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">
                                    <strong>Aucun compte bancaire disponible actuellement.</strong>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour ajouter un compte bancaire -->
<div class="modal fade" id="ajouterCompteModal" tabindex="-1" role="dialog" aria-labelledby="ajouterCompteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ajouterCompteModalLabel">Ajouter un compte bancaire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ajouterCompteForm">
                @csrf

                <div class="modal-body">
                    <!-- Champ numéro de compte -->
                    <div class="form-group">
                        <label for="numero_compte">Numéro de compte</label>
                        <input type="text" class="form-control" id="numero_compte" name="numero_compte" required>
                        <div class="invalid-feedback" id="numero_compte_error"></div>
                    </div>
                    <!-- Champ titulaire du compte -->
                    <div class="form-group">
                        <label for="titulaire_compte">Titulaire du compte</label>
                        <input type="text" class="form-control" id="titulaire_compte" name="titulaire_compte" required>
                        <div class="invalid-feedback" id="titulaire_compte_error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('assets/admin/src/plugins/datatables/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/admin/src/plugins/datatables/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/admin/src/plugins/datatables/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/admin/src/plugins/datatables/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/admin/src/plugins/datatables/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('assets/admin/src/plugins/datatables/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/admin/src/plugins/datatables/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/admin/vendors/scripts/datatable-setting.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Lorsque le formulaire est soumis
$('#ajouterCompteForm').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    
    // Désactiver le bouton de soumission pendant l'envoi
    $('button[type="submit"]').attr('disabled', true).text('Chargement...');

    // Envoyer la requête AJAX
    $.ajax({
        url: '{{ route('comptes.store') }}', // Route de votre API
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if(response.status_code === 201 && response.success) {
                // Fermer le modal et réinitialiser le formulaire
                $('#ajouterCompteModal').modal('hide');
                $('#ajouterCompteForm')[0].reset();

                // Afficher un message de succès
                Swal.fire({
                    icon: 'success',
                    title: 'Compte ajouté',
                    text: response.message, // Utiliser le message de la réponse
                });
                setTimeout(function() {
                            window.location.reload();
                        }, 1500);
            } else {
                // Afficher l'erreur
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: response.message, // Utiliser le message de la réponse
                });
            }

            // Réactiver le bouton de soumission
            $('button[type="submit"]').attr('disabled', false).text('Ajouter');
        },
        error: function(xhr) {
            // Gérer les erreurs de validation et afficher les messages
            var errors = xhr.responseJSON.errors;
            $('#numero_compte_error').text(errors.numero_compte ? errors.numero_compte[0] : '');
            $('#titulaire_compte_error').text(errors.titulaire_compte ? errors.titulaire_compte[0] : '');

            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez corriger les erreurs dans le formulaire.',
            });

            // Réactiver le bouton de soumission
            $('button[type="submit"]').attr('disabled', false).text('Ajouter');
        }
    });
});

</script>
@endsection

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection
