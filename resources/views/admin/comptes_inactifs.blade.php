@extends('admin.app')

@section('titre', "Liste de toutes les comptes bancaires inactifs")

@section('contenu')
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Liste de tous les comptes bancaires inactifs</h4>
                        </div>
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
                                <th>Gestionnaire</th>
                                <th>Date de création</th>
                                <th class="datatable-nosort">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($comptes as $compte)
                            <tr id="compte-{{ $compte->id }}">
                                <td>{{ $compte->id }}</td>
                                <td>{{ $compte->titulaire_compte }}</td>
                                <td>{{ $compte->numero_compte }}</td>
                                <td>{{ number_format($compte->solde, 2) }} €</td>
                                <td>{{ $compte->user->name ?? 'N/A' }}</td>
                                <td>{{ $compte->created_at->format('d-m-Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="btn btn-link font-24 p-0 line-height-1 no-arrow dropdown-toggle" href="#" role="button" data-toggle="dropdown">
                                            <i class="dw dw-more"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-icon-list">
                                            <!-- Voir opération -->
                                            <a class="dropdown-item" href="{{ route('operations.index', $compte->id) }}">
                                                <i class="fas fa-arrow-up"></i> Voir Opération
                                            </a>
                                            
                                            <!-- Voir Gestionnaire -->
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="voirGestionnaire({{ $compte->id }})">
                                                <i class="fas fa-user"></i> Voir Gestionnaire
                                            </a>
                                            <!-- Activer le compte -->
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="activerCompte({{ $compte->id }})">
                                                <i class="fas fa-check"></i> Activer
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <strong>Aucun compte bancaire inactif disponible actuellement.</strong>
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

<!-- Modal Voir Gestionnaire -->
<div class="modal fade" id="modalGestionnaire" tabindex="-1" role="dialog" aria-labelledby="modalGestionnaireLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGestionnaireLabel">Gestionnaire du compte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="gestionnaire-info">Chargement...</p>
            </div>
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
    // Fonction pour afficher les informations du gestionnaire dans un modal
    function voirGestionnaire(id) {
        $.ajax({
            url: '/dashboard/comptes/' + id + '/gestionnaire',
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#gestionnaire-info').html(
                        `<strong>Nom:</strong> ${response.data.gestionnaire.name}<br>
                         <strong>Email:</strong> ${response.data.gestionnaire.email}<br>
                         <strong>Rôle:</strong> ${response.data.gestionnaire.role}`);
                    $('#modalGestionnaire').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: response.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Impossible de récupérer les informations du gestionnaire.',
                });
            }
        });
    }

    // Fonction pour activer un compte bancaire
    function activerCompte(id) {
     Swal.fire({
         title: 'Êtes-vous sûr?',
         text: "Voulez-vous vraiment activer ce compte bancaire?",
         icon: 'warning',
         showCancelButton: true,
         confirmButtonColor: '#3085d6',
         cancelButtonColor: '#d33',
         confirmButtonText: 'Oui, activer!',
     }).then((result) => {
         if (result.isConfirmed) {
             $.ajax({
                 url: '/dashboard/comptes/' + id + '/activer', // Assurez-vous que la route soit correcte
                 type: 'POST',
                 data: {
                     _token: '{{ csrf_token() }}', // Ajoute le jeton CSRF ici
                 },
                 success: function(response) {
                     if (response.success) {
                         Swal.fire(
                             'Activé!',
                             'Le compte bancaire a été activé.',
                             'success'
                         );
                         window.location.reload(); // Rafraîchit la page après l'activation
                     } else {
                         Swal.fire({
                             icon: 'error',
                             title: 'Erreur',
                             text: response.message,
                         });
                     }
                 },
                 error: function() {
                     Swal.fire({
                         icon: 'error',
                         title: 'Erreur',
                         text: 'Une erreur est survenue lors de l\'activation du compte.',
                     });
                 }
             });
         }
     });
 }
</script>
@endsection
