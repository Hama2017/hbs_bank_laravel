@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Liste des Transactions
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Compte Source</th>
                                    <th>Compte Destination</th>
                                    <th>Montant</th>
                                    <th>Raison</th>
                                    <th>Guichet</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->id }}</td>
                                        <td>{{ $transaction->account_number_from }}</td>
                                        <td>{{ $transaction->account_number_to }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->reason }}</td>

                                        <td>
                                            @if ($transaction->user)
                                            {{ $transaction->user->id.' - '.$transaction->user->first_name.' '.$transaction->user->last_name }}
            @else

            @endif
                                           </td>
                                        <td>{{ $transaction->created_at }}</td>
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
