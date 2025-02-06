
@extends('admin.app')

@section('titre', "Opérations du Compte " . $compte->numero_compte)

@section('contenu')
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-md-6 col-sm-12">
                        <div class="title">
                            <h4>Opérations du compte : {{ $compte->numero_compte }}</h4>
                            <a href="{{ route('operations.create') }}" class="btn btn-primary">Nouvelle Opération</a>
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
                                <th>Date</th>
                                <th>Type</th>
                                <th>Montant (€)</th>
                                <th>Solde après opération (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($operations as $operation)
                            <tr>
                                <td>{{ $operation->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge {{ $operation->type == 'depot' ? 'badge-success' : 'badge-danger' }}">
                                        {{ ucfirst($operation->type) }}
                                    </span>
                                </td>
                                <td>{{ number_format($operation->montant, 2) }}</td>
                                <td>{{ number_format($operation->solde, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucune opération enregistrée.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            

        </div>
    </div>
</div>

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

@endsection
