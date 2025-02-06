@extends('admin.app')

@section('titre', "Accès Interdit")

@section('contenu')
<div class="main-container">
    <div class="pd-ltr-20 xs-pd-20-10">
        <div class="min-height-200px">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="title">
                            <h4>Accès Interdit</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message d'Accès Interdit -->
            <div class="card-box mb-30">
                <div class="pb-20 text-center">
                    <h3>Vous n'avez pas l'autorisation d'accéder à cette page.</h3>
                    <p>Veuillez contacter l'administrateur si vous pensez que c'est une erreur.</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
