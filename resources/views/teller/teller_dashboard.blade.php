<!-- teller_dashboard.blade.php -->
@extends('layouts.teller')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Faire un dépôt</div>
                <div class="card-body">
                    <p><a href="#" data-bs-toggle="modal" data-bs-target="#depositModal">Faire un dépôt</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Faire un retrait</div>
                <div class="card-body">
                    <p><a href="#" data-bs-toggle="modal" data-bs-target="#withdrawModal">Faire un retrait</a></p>
                </div>
            </div>
        </div>
    </div>
</div>


   <!-- Modal pour le dépôt -->
<div class="modal fade" id="depositModal" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="depositModalLabel">Faire un dépôt</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('teller.deposit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="accountNumber">Numéro de compte :</label>
                        <input type="text" class="form-control" id="accountNumber" name="accountNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Montant :</label>
                        <input type="text" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour le retrait -->
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="withdrawModalLabel">Faire un retrait</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('teller.withdraw') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="accountNumber">Numéro de compte :</label>
                        <input type="text" class="form-control" id="accountNumber" name="accountNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Montant :</label>
                        <input type="text" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
