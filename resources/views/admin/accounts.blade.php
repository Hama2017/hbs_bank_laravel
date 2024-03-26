@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Liste des Comptes</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Type</th>
                                <th>Numéro de compte</th>
                                <th>Solde</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accounts as $account)
                            <tr>
                                <td>{{ $account->id }}</td>
                                <td>{{ $account->user->first_name.' '.$account->user->last_name }}</td>
                                <td>{{ $account->accountType->name }}</td>
                                <td>{{ $account->account_number }}</td>
                                <td>{{ $account->balance }}</td>
                                <td>{{ $account->status }}</td>
                                <td>
                                    <form action="{{ route('admin.accounts.toggle-status') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="account_id" value="{{ $account->id }}">
                                        <button type="submit" class="btn {{ $account->status === 'active' ? 'btn-danger' : 'btn-success' }}">
                                            {{ $account->status === 'active' ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
