<!-- customer_dashboard.blade.php -->
@extends('layouts.customer')



@section('content')
<div class="container">
    <div class="row">
        @if($currentAccount)
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">Compte Courant</div>
                <div class="card-body">
                    <p>Numéro de compte : {{ $currentAccount->account_number }}</p>
                    <div class="tooltip-btn">
                        <span class="tag bg-{{ $currentAccount->status === 'active' ? 'success' : 'danger' }} text-white border-0 py-1 px-2 float-end mt-1">{{ $currentAccount->status === 'active' ? 'Active' : 'Inactive' }}</span>
                    </div>                    <p>Solde : xxxxxxxxxx</p>

                    @if($currentAccount->status === 'active')
                    <a href="{{ route('current_account') }}" class="btn btn-primary">Accéder au compte</a>
                @endif
                </div>
            </div>
        </div>
        @else
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header">Compte Courant</div>
                <div class="card-body">
                    <p>Vous n'avez pas de compte courant.</p>

                    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#openCurrentAccountModal">Ouvrir un compte courant</a>
                </div>
            </div>
        </div>
        @endif

        @if($savingsAccount)
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-header">Compte d'Épargne</div>
                <div class="card-body">
                    <p>Numéro de compte : {{ $savingsAccount->account_number }}</p>
                    <div class="tooltip-btn">
                        <span class="tag bg-{{ $savingsAccount->status === 'active' ? 'success' : 'danger' }} text-white border-0 py-1 px-2 float-end mt-1">{{ $savingsAccount->status === 'active' ? 'Active' : 'Inactive' }}</span>
                    </div>
                                        <p>Solde : xxxxxxxx</p>

                                        @if($savingsAccount->status === 'active')
                                        <a href="{{ route('savingsAccount') }}" class="btn btn-primary">Accéder au compte</a>
                                    @endif

                </div>
            </div>
        </div>
        @else
        <div class="col-md-6 mt-3">
            <div class="card">
                <div class="card-header">Compte d'Épargne</div>
                <div class="card-body">
                    <p>Vous n'avez pas de compte d'épargne.</p>
                    <button id="openSavingsAccountButton" class="btn btn-primary">Ouvrir un compte d'épargne</button>

                </div>
            </div>
        </div>
        @endif
    </div>
</div>


<!-- Modal pour ouvrir un compte courant -->
<div class="modal fade" id="openCurrentAccountModal" tabindex="-1" role="dialog" aria-labelledby="openCurrentAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="openCurrentAccountModalLabel">Ouvrir un compte courant</h5>
                <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">
                <form id="openCurrentAccountForm">
                    @csrf
                    <div class="form-group">
                        <label for="package">Sélectionner un package :</label>
                        <select class="form-control" id="package" name="package">
                            @foreach($packages as $package)
                                <option value="{{ $package->id }}">{{ $package->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" id="openCurrentAccountButton" class="btn btn-primary">Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#openCurrentAccountButton').click(function(e) {

            var formData = $('#openCurrentAccountForm').serialize();

            $.ajax({
                type: 'POST',
                url: '/api/openCurrentAccount',
                data: formData,
                success: function(response) {
                    // Afficher une alerte de succès avec SweetAlert
                    Swal.fire({
                        icon: 'success',
                        title: 'Compte courant créé avec succès!',
                        showConfirmButton: false,
                        timer: 2000 // Fermer automatiquement l'alerte après 2 secondes
                    });
                    // Fermer le modal après un court délai
                    setTimeout(function() {
                        $('#openCurrentAccountModal').modal('hide');
                        // Actualiser la page ou effectuer d'autres actions si nécessaire
                    }, 2000); // 2000 milliseconds = 2 seconds
                },
                error: function(error) {
                    console.log(error);
                    // Afficher une erreur à l'utilisateur ou effectuer d'autres actions si nécessaire
                }
            });
        });
    });

    </script>



<!-- Script AJAX pour ouvrir un compte d'épargne -->
<script>
    $(document).ready(function() {
        $('#openSavingsAccountButton').click(function(e) {

            // Envoyer une requête AJAX pour créer le compte d'épargne
            $.ajax({
                type: 'POST',
                url: '/api/openSavingsAccount',
                data: {
                _token: '{{ csrf_token() }}',
        // Autres données de formulaire ici
                 },
                success: function(response) {

                      // Afficher une alerte de succès avec SweetAlert
                      Swal.fire({
                        icon: 'success',
                        title: "Compte d'épargne créé avec succès !",
                        showConfirmButton: false,
                        timer: 2000 // Fermer automatiquement l'alerte après 2 secondes
                    });
                    // Fermer le modal après un court délai
                    setTimeout(function() {
                        $('#openCurrentAccountModal').modal('hide');
                        // Actualiser la page ou effectuer d'autres actions si nécessaire
                    }, 2000); // 2000 milliseconds = 2 seconds

                },
                error: function(error) {
                    console.log(error);
                    // Afficher une alerte d'erreur à l'utilisateur si nécessaire
                    swal("Erreur lors de la création du compte d'épargne!", {
                        icon: "error",
                    });
                }
            });
        });
    });
</script>


</body>

@endsection



