<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AccountNumber;
use App\Models\SubscriptionPackage;
use Illuminate\Support\Facades\Auth;

class TellerDashboardController extends Controller
{
    public function index()
    {
        return view('teller/teller_dashboard');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'accountNumber' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        // Récupérer les informations du compte
        $account = AccountNumber::where('account_number', $request->accountNumber)->first();

        // Vérifier si le compte existe et s'il est actif
        if (!$account || $account->status !== 'active') {
            return redirect()->back()->with('error', 'Le compte est invalide ou inactif.');
        }

        // Créer une nouvelle transaction pour le depot
        Transaction::create([
            'account_number_from' => "53795691759",
            'account_number_to' => $request->accountNumber, // Compte de la banque
            'id_user' => Auth::id(), // Récupérer l'ID de l'utilisateur actuellement authentifié
            'amount' => $request->amount, // Montant positif pour le depot
            'reason' => 'Dépot par guichet LUI MM',
        ]);

        return redirect()->back()->with('success', 'Le dépôt a été effectué avec succès.');
    }

    public function withdraw(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'accountNumber' => 'required',
            'amount' => 'required|numeric|min:0',
        ]);

        // Vérifier si le compte existe et s'il est actif
        $account = AccountNumber::where('account_number', $request->accountNumber)
            ->where('status', 'active')
            ->first();

        if (!$account) {
            return redirect()->back()->with('error', 'Le compte est invalide ou inactif.');
        }

        // Vérifier si le compte a suffisamment de fonds pour le retrait
        $currentBalance = $account->balance();
        $withdrawalAmount = $request->amount;

        if ($currentBalance < $withdrawalAmount) {
            return redirect()->back()->with('error', 'Fonds insuffisants pour effectuer ce retrait.');
        }

        // Créer une nouvelle transaction pour le retrait
        Transaction::create([
            'account_number_from' => $request->accountNumber,
            'account_number_to' => $request->accountNumber, // Compte de la banque
            'id_user' => Auth::id(), // Récupérer l'ID de l'utilisateur actuellement authentifié
            'amount' => -$withdrawalAmount, // Montant négatif pour le retrait
            'reason' => 'Retrait par guichet LUI MM',
        ]);

        return redirect()->back()->with('success', 'Retrait effectué avec succès.');
    }

}
