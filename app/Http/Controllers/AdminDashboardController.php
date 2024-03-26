<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AccountType;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\AccountNumber;

class AdminDashboardController extends Controller
{

    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    public function accounts()
    {
        $accounts = AccountNumber::with('user', 'accountType')->get();
        foreach ($accounts as $account) {
            $account->balance = $account->balance();
        }

        return view('admin.accounts', compact('accounts'));

    }

    public function transactions()
    {
        $transactions = Transaction::with('user')->get();
        return view('admin.transactions', compact('transactions'));
    }

    public function toggleAccountStatus(Request $request)
    {
        $account = AccountNumber::findOrFail($request->account_id);

        // Toggle status
        $account->status = $account->status === 'active' ? 'inactive' : 'active';
        $account->save();

        return redirect()->back()->with('success', 'Statut du compte modifié avec succès.');
    }

}


