@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Liste des utilisateurs</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Comptes</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <ul>
                                    @foreach($user->accountNumbers as $account)
                                    <li>{{ $account->account_number }} - {{ $account->accountType->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <form action="{{ route('admin.toggleAccount', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                        {{ $user->is_active ? 'Désactiver' : 'Activer' }} le compte
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
@endsection
