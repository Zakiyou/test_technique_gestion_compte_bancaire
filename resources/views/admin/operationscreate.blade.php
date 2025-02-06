@extends('admin.app')

@section('titre', "Ajouter une Opération")

@section('contenu')
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Ajout d'une Opération Bancaire</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pd-20 card-box mb-30">

                <form id="operationForm">
                    @csrf
                
                    <div class="form-group">
                        <label for="compte_id">Compte Bancaire</label>
                        <select id="compte_id" name="compte_id" class="form-control">
                            <option value="">Sélectionnez un compte</option>
                            @foreach ($comptes as $compte)
                                <option value="{{ $compte->id }}">
                                    {{ $compte->numero_compte }} - {{ number_format($compte->solde, 2) }} €
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="type_operation">Type d'Opération</label>
                        <select id="type_operation" name="type_operation" class="form-control">
                            <option value="depot">Dépôt</option>
                            <option value="retrait">Retrait</option>
                        </select>
                    </div>
                
                    <div class="form-group">
                        <label for="montant">Montant (€)</label>
                        <input type="number" id="montant" name="montant" step="0.01" class="form-control" placeholder="Entrez le montant">
                    </div>
                
                    <button type="submit" class="btn btn-primary">Effectuer l'Opération</button>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
    $('#operationForm').on('submit', function(e) {
        e.preventDefault(); // Empêche le rechargement de la page

        $.ajax({
            type: 'POST',
            url: '{{ route('operations.store') }}',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Opération réussie 🎉',
                        text: response.message,
                        showCancelButton: true,
                        confirmButtonText: 'Voir les opérations',
                        cancelButtonText: 'Effectuer une autre',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Rediriger vers la liste des opérations du compte sélectionné
                            let compteId = $('#compte_id').val();
                            window.location.href = `/dashboard/operations/${compteId}`;
                        } else {
                            $('#operationForm')[0].reset(); // Réinitialiser le formulaire
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur 🚨',
                        text: response.message || 'Une erreur inattendue est survenue.',
                        confirmButtonColor: '#dc3545',
                        timer: 5000,
                        showConfirmButton: true
                    });
                }
            },
            error: function(xhr) {
                let response = xhr.responseJSON;
                let errorMessage = "Une erreur est survenue. Veuillez réessayer.";
                
                if (response && response.message) {
                    errorMessage = response.message;
                } else if (response && response.errors) {
                    let firstError = Object.values(response.errors)[0][0];
                    errorMessage = firstError;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Erreur ❌',
                    text: errorMessage,
                    confirmButtonColor: '#dc3545',
                    timer: 5000,
                    showConfirmButton: true
                });
            }
        });
    });
});

</script>
@endsection
