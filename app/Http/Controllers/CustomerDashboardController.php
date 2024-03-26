<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use App\Models\AccountNumber;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function index()
    {
        // Récupérer le compte courant de l'utilisateur s'il existe
        $currentAccount = AccountNumber::where('id_user', Auth::id())
                            ->where('account_number', 'like', '537%')
                            ->first();

        // Récupérer le compte épargne de l'utilisateur s'il existe
        $savingsAccount = AccountNumber::where('id_user', Auth::id())
                            ->where('account_number', 'like', '630%')
                            ->first();

                              // Récupérer tous les packages
    $packages = Package::all();


        return view('customer/customer_dashboard', compact('currentAccount', 'savingsAccount', 'packages'));
    }

    public function currentAccount()
    {
        $user = auth()->user();
        $currentAccount = $user->accountNumbers()->where('id_account_type', 1)->first();

        // Vérifier si un compte courant a été trouvé pour l'utilisateur
        if (!$currentAccount) {
            abort(404, 'Aucun compte courant trouvé pour cet utilisateur.');
        }

        // Passer le numéro de compte à la vue
        return view('customer/current_account', ['currentAccountNumber' => $currentAccount->account_number]);
    }

    public function savingsAccount()
    {
        $user = auth()->user();
        $savingsAccount = $user->accountNumbers()->where('id_account_type', 2)->first();
        $savingsAccountNumber = $savingsAccount ? $savingsAccount->account_number : null;

        return view('customer/savings_account', compact('savingsAccountNumber'));
    }


}


