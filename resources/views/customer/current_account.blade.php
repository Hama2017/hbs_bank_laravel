<!-- current_account.blade.php -->
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
            <h1 class="display-1 my-2"><span id="currentAccountBalance">chargement...</span> F CFA</h1>
            <p class="text-muted mb-2">Solde du compte courant</p>
            <p id="currentAccountNumber">{{ $currentAccountNumber }}</p>
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
        <ul class="list-group list-group-flush bg-none" id="transactionList">
            <!-- Les transactions seront chargées ici via AJAX -->
        </ul>
    </div>
</div>


<div class="row mb-3">
    <div class="col">
        <h6 class="title">Mes cartes</h6>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12 px-0">
        <div class="swiper-container cardswiper swiper-container-initialized swiper-container-horizontal swiper-container-pointer-events">
            <div class="swiper-wrapper" id="swiper-wrapper">
                <!-- Les cartes virtuelles seront chargées ici via AJAX -->
            </div>
        </div>
    </div>
</div>


@endsection

@section('js')
<script>

  $(document).ready(function() {
    // Charger le solde du compte courant via AJAX
    function loadCurrentAccountBalance() {
        $.ajax({
            url: '/api/currentAccountBalance',
            type: 'GET',
            success: function(response) {
                $('#currentAccountBalance').text(response.balance);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // Charger la liste des transactions du compte courant via AJAX
    function loadCurrentAccountTransactions() {
        $.ajax({
            url: '/api/currentAccountTransactions',
            type: 'GET',
            success: function(response) {
                var currentAccountNumber = $('#currentAccountNumber').text();
                var transactionsHtml = '';
                var transactions = response.transactions;

                transactions.forEach(function(transaction) {
                    var icon = transaction.amount >= 0 ? '<i class="bi bi-arrow-down-left-circle size-32"></i>' : '<i class="bi bi-arrow-up-right-circle size-32"></i>';
                    var amount = Math.abs(transaction.amount);
                    var amountHtml = (transaction.amount <= 0) ? '- ' + amount : '+ ' + amount;
                    var amountColor = (transaction.amount <= 0) ? '#f03b3b' : '';

                    transactionsHtml += '<li class="list-group-item"><div class="row"><div class="col-auto">' + icon + '</div><div class="col align-self-center ps-0"><p class="text-color-theme mb-0" style="color:' + amountColor + '">' + amountHtml + ' F CFA</p><p class="text-muted size-12">' + transaction.reason + '</p></div><div class="col align-self-center text-end"><p class="mb-0">' + transaction.formatted_date + '</p></div></div></li>';
                });

                $('#transactionList').html(transactionsHtml);
            },
            error: function(error) {
                console.log(error);
            }
        });
    }

    // Charger le solde du compte courant et la liste des transactions toutes les 5 secondes
    setInterval(function() {
        loadCurrentAccountBalance();
        loadCurrentAccountTransactions();
    }, 1000);
});


$(document).ready(function() {
    $('#createCreditCardButton').click(function(e) {
        e.preventDefault();

        var formData = $('#createCreditCardForm').serialize();

        $.ajax({
            type: 'POST',
            url: '/api/createCreditCard',
            data: formData,
            success: function(response) {
                // Afficher une alerte de succès avec SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: response.message
                });
                // Fermer la modal
                $('#createCreditCardModal').modal('hide');
            },
            error: function(error) {
                // Afficher une alerte d'erreur avec SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.responseJSON.message
                });
            }
        });
    });
});

function formatCardNumber(cardNumber) {
    // Supprimer tous les espaces existants
    cardNumber = cardNumber.replace(/\s/g, '');

    // Ajouter des espaces entre chaque groupe de 4 chiffres
    return cardNumber.replace(/(\d{4})/g, '$1 ').trim();
}

$(document).ready(function() {
    // Charger les cartes virtuelles via AJAX
    $.ajax({
        url: '/api/getVirtualCards',
        type: 'GET',
        success: function(response) {
            var cardsHtml = '';
            response.cards.forEach(function(card) {
                cardsHtml += '<div class="swiper-slide swiper-slide-active" role="group" aria-label="1 / 3">';
                cardsHtml += '<div class="card">';
                cardsHtml += '<div class="card-body">';
                cardsHtml += '<div class="row mb-3">';
                cardsHtml += '<div class="col-auto align-self-center">';
                cardsHtml += '<img src="assets/img/masterocard.png" alt="">';
                cardsHtml += '</div>';
                cardsHtml += '<div class="col align-self-center text-end">';
                cardsHtml += '<p class="small">';
                cardsHtml += '<span class="text-uppercase size-10">EXP</span><br>';
                cardsHtml += '<span class="text-muted">' + card.exp_date + '</span>';
                cardsHtml += '</p>';
                cardsHtml += '</div>';
                cardsHtml += '</div>';
                cardsHtml += '<div class="row">';
                cardsHtml += '<div class="col-12">';
                cardsHtml += '<h4 class="fw-normal mb-2">';
                cardsHtml += card.amount ;
                cardsHtml += '<p class="mb-0 mt-3 text-muted size-12">' + formatCardNumber(card.number) + '</p>';
                cardsHtml += '</h4>';
                cardsHtml += '<p class="text-muted size-12">HBS Virtuel Card</p>';
                cardsHtml += '</div>';
                cardsHtml += '</div>';
                cardsHtml += '</div>';
                cardsHtml += '</div>';
                cardsHtml += '</div>';
            });
            $('#swiper-wrapper').html(cardsHtml);
        },
        error: function(error) {
            console.log(error);
        }
    });
});



$(document).ready(function() {
    // Gérer le clic sur le bouton "Beneficiaires" pour ouvrir le modal
    $('#addBeneficiaryButton').click(function() {
        var accountNumber = $('#accountNumber').val();

        // Envoyer la requête AJAX pour ajouter un bénéficiaire
        $.ajax({
            type: 'POST',
            url: '/api/addBeneficiary',
            data: {  "_token": "{{ csrf_token() }}",accountNumber: accountNumber },

            success: function(response) {
                // Afficher une alerte de succès avec SweetAlert
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: response.message
                });

                // Fermer le modal après le succès
                $('#addBeneficiaryModal').modal('hide');
            },
            error: function(error) {
                // Afficher une alerte d'erreur avec SweetAlert
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: error.responseJSON.message
                });
            }
        });
    });
});



$('#transferSubmit').click(function() {
    var amount = $('#amount').val();
    var beneficiary = $('#beneficiary').val();

    // Effectuez des validations supplémentaires si nécessaire

    $.ajax({
        type: 'POST',
        url: '/api/transfer',
        data: {
            amount: amount,
            beneficiary: beneficiary
        },
        success: function(response) {
            // Afficher une alerte de succès avec SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Succès',
                text: response.message
            });
            // Fermer le modal après succès
            $('#transferModal').modal('hide');
        },
        error: function(error) {
            // Afficher une alerte d'erreur avec SweetAlert
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: error.responseJSON.message
            });
        }
    });
});


$(document).ready(function() {
    // Charger les bénéficiaires via AJAX
    $.ajax({
        url: '/api/beneficiaries',
        type: 'GET',
        success: function(response) {
            var beneficiaries = response.beneficiaries;

            // Remplir le select avec les options des bénéficiaires
            beneficiaries.forEach(function(beneficiary) {
                $('#beneficiary').append('<option value="' + beneficiary.account_number + '">' + beneficiary.account_number + ' - ' + beneficiary.user.first_name + ' ' + beneficiary.user.last_name + '</option>');
            });
        },
        error: function(error) {
            console.log(error);
        }
    });
});


</script>
@endsection




@section("footer")
<footer class="footer">
    <div class="container">
        <ul class="nav nav-pills nav-justified">
            <li class="nav-item centerbutton">
                <div class="nav-link">
                    <span class="theme-radial-gradient">
                        <i class="close bi bi-x"></i>
                        <img src="assets/img/centerbutton.svg" class="nav-icon" alt="">
                    </span>
                    <div class="nav-menu-popover justify-content-between">
                        <button type="button" class="btn btn-lg btn-icon-text"  data-bs-toggle="modal" data-bs-target="#createCreditCardModal">
                            <i class="bi bi-credit-card size-32"></i><span>Carte</span>
                        </button>

                        <button type="button" class="btn btn-lg btn-icon-text"  data-bs-toggle="modal" data-bs-target="#transferModal">
                            <i class="bi bi-arrow-up-right-circle size-32"></i><span>Transfert</span>
                        </button>

                        <button type="button" class="btn btn-lg btn-icon-text" data-bs-toggle="modal" data-bs-target="#addBeneficiaryModal">
                            <i class="bi bi-receipt size-32"></i><span>Beneficiaires</span>
                        </button>

                    </div>
                </div>
            </li>

        </ul>
    </div>
</footer>


<!-- Modal pour créer une carte -->
<div class="modal fade" id="createCreditCardModal" tabindex="-1" role="dialog" aria-labelledby="createCreditCardModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createCreditCardModalLabel">Créer une carte</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="createCreditCardForm">
                    @csrf
                    <div class="form-group">
                        <label for="amount">Solde :</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="button" id="createCreditCardButton" class="btn btn-primary">Générer Carte</button>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal pour ajouter un bénéficiaire -->
<div class="modal fade" id="addBeneficiaryModal" tabindex="-1" role="dialog" aria-labelledby="addBeneficiaryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBeneficiaryModalLabel">Ajouter un bénéficiaire</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addBeneficiaryForm">
                    @csrf
                    <div class="form-group">
                        <label for="accountNumber">Numéro de compte du bénéficiaire :</label>
                        <input type="text" class="form-control" id="accountNumber" name="accountNumber" required>
                    </div>
                    <button type="button" class="btn btn-primary" id="addBeneficiaryButton">Valider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Faire un transfert</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="transferForm">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Montant</label>
                        <input type="text" class="form-control" id="amount" name="amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="beneficiary" class="form-label">Bénéficiaire</label>
                        <select class="form-select" id="beneficiary" name="beneficiary" required>
                            <!-- Options des bénéficiaires seront chargées ici -->
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary" id="transferSubmit">Faire transfert</button>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
