<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPackage extends Model
{
    use HasFactory;
    protected $fillable = ['account_number', 'id_package'];

    public function user()
    {
        return $this->belongsTo(User::class, 'account_number');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'id_package');
    }
}
