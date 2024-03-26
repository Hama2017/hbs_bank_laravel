<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountNumber extends Model
{
    use HasFactory;
    protected $fillable = ['id_user', 'id_account_type', 'account_number', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function accountType()
    {
        return $this->belongsTo(AccountType::class, 'id_account_type');
    }

     // Définir la relation avec les transactions
     public function transactions()
     {
         return $this->hasMany(Transaction::class, 'account_number_from');
     }


    public function balance()
    {
        // Calculer la somme des montants des transactions entrantes pour le compte
        $balance = Transaction::where('account_number_to', $this->account_number)->sum('amount');

        return $balance;
    }

}
