<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = ['account_number_from', 'account_number_to', 'amount', 'reason', 'id_user'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function destinationAccount()
    {
        return $this->belongsTo(AccountNumber::class, 'account_number_to', 'account_number');
    }

    // Définir la relation avec le compte source si nécessaire
    public function sourceAccount()
    {
        return $this->belongsTo(AccountNumber::class, 'account_number_from', 'account_number');
    }
}
