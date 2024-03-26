<!-- savings_account.blade.php -->
@extends('layouts.customer')

@section('content')

<div class="card shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col px-0 align-self-center">
                <p class="mb-0 text-color-theme">M. {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
            </div>
        </div>
    </div>
    <div class="card theme-bg text-white border-0 text-center">
        <div class="card-body">
            <h1 class="display-1 my-2"><span id="savingsAccountBalance">chargement...</span> F CFA</h1>
            <p class="text-muted mb-2">Solde du compte d'épargne</p>
            <p id="savingsAccountNumber">{{ $savingsAccountNumber }}</p>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col">
        <h6 class="title">Transactions</h6>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 px-0">
        <ul class="list-group list-group-flush bg-none" id="savingsTransactionList">
            <!-- Les transactions seront chargées ici via AJAX -->
        </ul>
    </div>
</div>

@endsection

@section('js')
<script>

$(document).ready(function() {
    // Charger le solde du compte d'épargne via AJAX
    function loadSavingsAccountBalance() {
        $.ajax({
            url: '/api/savingsAccountBalance',
            type: 'GET',
            success: function(response) {
                $('#savingsAccountBalance').text(response.balance);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // Charger la liste des transactions du compte d'épargne via AJAX
    function loadSavingsAccountTransactions() {
        $.ajax({
            url: '/api/savingsAccountTransactions',
            type: 'GET',
            success: function(response) {
                var savingsAccountNumber = $('#savingsAccountNumber').text();
                var transactionsHtml = '';
                var transactions = response.transactions;

                transactions.forEach(function(transaction) {
                    var icon = transaction.amount >= 0 ? '<i class="bi bi-arrow-down-left-circle size-32"></i>' : '<i class="bi bi-arrow-up-right-circle size-32"></i>';
                    var amount = Math.abs(transaction.amount);
                    var amountHtml = (transaction.amount <= 0) ? '- ' + amount : '+ ' + amount;
                    var amountColor = (transaction.amount <= 0) ? '#f03b3b' : '';

                    transactionsHtml += '<li class="list-group-item"><div class="row"><div class="col-auto">' + icon + '</div><div class="col align-self-center ps-0"><p class="text-color-theme mb-0" style="color:' + amountColor + '">' + amountHtml + ' F CFA</p><p class="text-muted size-12">' + transaction.reason + '</p></div><div class="col align-self-center text-end"><p class="mb-0">' + transaction.formatted_date + '</p></div></div></li>';
                });

                $('#savingsTransactionList').html(transactionsHtml);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // Charger le solde du compte d'épargne et la liste des transactions toutes les 5 secondes
    setInterval(function() {
        loadSavingsAccountBalance();
        loadSavingsAccountTransactions();
    }, 5000);
});

</script>
@endsection
