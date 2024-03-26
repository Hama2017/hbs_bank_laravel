<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiary extends Model
{
    use HasFactory;
    protected $fillable = ['id_user', 'account_number'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
